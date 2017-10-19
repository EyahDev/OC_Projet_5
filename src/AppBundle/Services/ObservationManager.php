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

    public function getObservations() {
        // Récupération de tous les articles existant
        $observation = $this->em->getRepository('AppBundle:Observation')->findAll();

        // Retourne l'article récupéré
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

        // Retourne les articles associés à la catégorie
        return $observations;
    }

    public function getObservationForm() {
        // Création d'une nouvelle observation
        $observation = new Observation();

        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create('AppBundle\Form\Observations\CreateObservationType', $observation);

        // Retourne le formulaire
        return $form;
    }

    public function setNewObservation(Observation $observation, User $user) {
        // Définition de l'utilisateur créateur de l'observation
        $observation->setObserver($user);

        // D2finition de la date de la nouvelle observation
        $observation->setObservationDate(new \DateTime());

        // Récupération du fichier original
        $file = $observation->getPhotoPath();

        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('observations_directory');

        // Renommage du fichier
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // Déplacement du fichier dans le dossiers des observations
        $file->move($path, $fileName);

        // Ajout de l'image dans l'observation
        $observation->setPhotoPath("uploads/observations_files/".$fileName);

        // Ajout de l'observation à l'espece
        $observation->getSpecies()->addObservation($observation);

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
            $observation->setValidate(true);
            $observation->setValidateDate(new \DateTime());
            $observation->setValidator($user);
        }

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }
}
