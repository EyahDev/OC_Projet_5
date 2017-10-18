<?php

namespace AppBundle\Services;

use AppBundle\Entity\Observation;
use AppBundle\Entity\Species;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ObservationManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em, RequestStack $request, SessionInterface $session) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
    }
    
    public function getUser($id) {
        // Récupération de la liste de toutes les catégories depuis le repository
        $user = $this->em->getRepository('AppBundle:User')->find($id);

        // Retourne l'utlisateur courant
        return $user;
    }

    public function getSpecie($id) {
        // Récupération de l'espèce par son id depuis le repository
        $specie = $this->em->getRepository('AppBundle:Specie')->findOneBy(array('id' => $id));

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
        $observation = $this->em->getRepository('AppBundle:Observation')->findOneBy(array('id' => $id));

        // Retourne l'observation récupérée
        return $observation;
    }

    public function getObservationsByUser($user) {
        // Récupération des observation par utilisateur
        $observations = $this->getUser($user)->getObservations();

        // Retourne les articles associés à la catégorie
        return $observations;
    }

    public function getSpecieByObservation($observation) {
        // Récupération des espèces par observation
        $specie = $this->getObservation($observation)->getSpecie();

        // Retourne les articles associés à la catégorie
        return $specie;
    }
}
