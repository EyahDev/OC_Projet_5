<?php

namespace AppBundle\Services;

use AppBundle\Entity\Observation;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ObservationManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $container;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em, RequestStack $request, SessionInterface $session, ContainerInterface $container) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->container = $container;
    }

    public function getSpecie($id) {
        // Récupération de l'espèce par son id depuis le repository
        $specie = $this->em->getRepository('AppBundle:Species')->find($id);

        // Retourne l'espèce
        return $specie;
    }

    public function getObservationsUnvalidated() {
        // Récupération de toutes observations existantes
        $observation = $this->em->getRepository('AppBundle:Observation')->findBy(array('validate' => null));

        // Retourne toutes les observations
        return $observation;
    }

    public function getObservation($id) {
        // Récupération d'une observation par son id
        $observation = $this->em->getRepository('AppBundle:Observation')->find($id);

        // Retourne l'observation récupérée
        return $observation;
    }

    public function getObservationsByUser($user) {
        // Récupération des observation par utilisateur
        $observations = $this->em->getRepository('AppBundle:Observation')->findBy(array('observer' => $user));

        // Retourne les bbservations de l'utilisateur
        return $observations;
    }

    public function getObservationForm() {
        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create('AppBundle\Form\Observations\CreateObservationType');

        // Retourne le formulaire
        return $form;
    }

    public function getObservationForValidationForm($id) {
        $observation = $this->getObservation($id);

        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create('AppBundle\Form\Observations\ModifObservationType', $observation);

        // Retourne le formulaire
        return $form;
    }

    public function setRefusedObservation(Observation $observation, User $user) {
        // Hydratation des valeurs de refus
        $observation->setValidateDate(new \DateTime());
        $observation->setValidate(false);
        $observation->setValidator($user);

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    public function setAcceptedObservation(Observation $observation, User $user) {
        // Hydratation des valeurs d'acceptation
        $observation->setValidateDate(new \DateTime());
        $observation->setValidate(true);
        $observation->setValidator($user);

        // Remplissage du ordre et de la famille si il est null
        if ($observation->getType() == null && $observation->getFamily() == null) {
            $observation->setType($observation->getSpecies()->getType());
            $observation->setFamily($observation->getSpecies()->getFamily());
        }

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    public function setNewObservation($observation, User $user) {
        // Création d'une nouvelle Observation
        $newObservation = new Observation();

        // Hydratation des valeurs
        $newObservation->setBirdNumber($observation['birdNumber']);
        $newObservation->setEggsNumber($observation['eggsNumber']);
        $newObservation->setEggsDescription($observation['eggsDescription']);
        $newObservation->setLatitude($observation['latitude']);
        $newObservation->setLongitude($observation['longitude']);
        $newObservation->setAltitude($observation['altitude']);
        $newObservation->setObservationDescription($observation['observationDescription']);

        // Définition de l'utilisateur créateur de l'observation
        $newObservation->setObserver($user);

        // Définition de la date de la nouvelle observation
        $newObservation->setObservationDate(new \DateTime());

        // Récupération du fichier original
        $files = $observation['photoPath'];

        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('observations_directory');

        $filespath = array();

        foreach ($files as $file) {
            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Déplacement du fichier dans le dossiers des observations
            $file->move($path, $fileName);

            // Ajout de l'image dans l'observation
            array_push($filespath,"uploads/observations_files/".$fileName);
        }

        // Ajout des images
        $newObservation->setPhotoPath($filespath);

        // Vérification si le nom commun ou le nom scientifique a été choisi
        if ($observation['vernacularName'] != null) {
            $newObservation->setSpecies($observation['vernacularName']);
        } elseif ($observation['species'] != null) {
            $newObservation->setSpecies($observation['species']);
        }

        if ($newObservation->getSpecies() != null) {
            // Ajout du type dans l'observation
            $newObservation->setType($newObservation->getSpecies()->getType());

            // Ajout du type dans l'observation
            $newObservation->setFamily($newObservation->getSpecies()->getFamily());

            // Ajout de l'observation à l'espece
            $newObservation->getSpecies()->addObservation($newObservation);

            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
                $newObservation->setValidate(true);
                $newObservation->setValidateDate(new \DateTime());
                $newObservation->setValidator($user);
            }
        }

        // Enregistrement
        $this->em->persist($newObservation);
        $this->em->flush();
    }
}
