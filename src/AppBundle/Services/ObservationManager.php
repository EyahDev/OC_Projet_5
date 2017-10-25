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

    /**
     * ObservationManager constructor.
     * @param FormFactoryInterface $formBuilder
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     * @param SessionInterface $session
     * @param ContainerInterface $container
     */
    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em, RequestStack $request, SessionInterface $session, ContainerInterface $container) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->container = $container;
    }

    /**
     * Récupération de toutes les observations non validées
     *
     * @return Observation[]|array
     */
    public function getObservationsUnvalidated() {
        // Récupération de toutes observations existantes
        $observation = $this->em->getRepository('AppBundle:Observation')->findBy(array('validate' => null));

        // Retourne toutes les observations
        return $observation;
    }

    /**
     * Récupération d'une observation par son id
     *
     * @param $id
     * @return Observation|null|object
     */
    public function getObservation($id) {
        // Récupération d'une observation par son id
        $observation = $this->em->getRepository('AppBundle:Observation')->find($id);

        // Retourne l'observation récupérée
        return $observation;
    }

    /**
     * Récupération des observations de l'utilisateur connecté
     *
     * @param $user
     * @return Observation[]|array
     */
    public function getObservationsByUser($user) {
        // Récupération des observation par utilisateur
        $observations = $this->em->getRepository('AppBundle:Observation')->findBy(array('observer' => $user));

        // Retourne les bbservations de l'utilisateur
        return $observations;
    }

    /**
     * Récupération du formulaire de saisie d'une observation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getObservationForm() {
        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create('AppBundle\Form\Observations\CreateObservationType');

        // Retourne le formulaire
        return $form;
    }

    /**
     * Récupération du formulaire de validation d'une observation
     *
     * @param $id
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getObservationForValidationForm($id) {
        $observation = $this->getObservation($id);

        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create('AppBundle\Form\Observations\ModifObservationType', $observation);

        // Retourne le formulaire
        return $form;
    }

    /**
     * Récupération du formulaire l'observation à modifier
     *
     * @param $id
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getObservationForModifyForm($id) {
        $observation = $this->getObservation($id);

        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create('AppBundle\Form\Observations\ModifObservationByObserverType', $observation);

        // Retourne le formulaire
        return $form;
    }

    /**
     * Modification d'une observation
     *
     * @param Observation $observation
     */
    public function setUpdatedObservation(Observation $observation) {
        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('observations_directory');

        // Récupération des nouveaux fichiers
        $newFile = $observation->getPhotoPath();

        if ($newFile != null) {
            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$newFile->guessExtension();

            // Déplacement du fichier dans le dossiers des observations
            $newFile->move($path, $fileName);

            // Récupération du nouveau chemin
            $filePath = "uploads/observations_files/".$fileName;

            // Ajout des images
            $observation->setPhotoPath($filePath);
        }

        // Vér
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
            // Nouvelle date de validation suite à la modification par un pro ou un admin
            $observation->setValidateDate(new \DateTime());
        } else {

            // Nouvelle soumission à validation
            $observation->setValidate(null);
        }

        // Passage à true de la modification de l'observation
        $observation->setEditObservation(true);

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    /**
     * @param Observation $observation
     * @param User $user
     */
    public function setRefusedObservation(Observation $observation, User $user) {
        // Hydratation des valeurs de refus
        $observation->setValidateDate(new \DateTime());
        $observation->setValidate(false);
        $observation->setValidator($user);

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    /**
     * Acceptation de l'observation par un pro ou un admin
     *
     * @param Observation $observation
     * @param User $user
     */
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

    /**
     * Ajout d'une nouvelle observation
     *
     * @param $observation
     * @param User $user
     */
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
        $file = $observation['photoPath'];

        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('observations_directory');

        // Renommage du fichier
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Déplacement du fichier dans le dossiers des observations
        $file->move($path, $fileName);

        // Ajout de l'image dans l'observation
        $filePath = "uploads/observations_files/".$fileName;

        // Ajout des images
        $newObservation->setPhotoPath($filePath);

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
