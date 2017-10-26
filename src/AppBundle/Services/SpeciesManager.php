<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SpeciesManager
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
     * Récupération des espèces
     *
     * @return \AppBundle\Entity\Species[]|array
     */
    public function getSpecies() {
        // Récupération de toutes les espèces
        $species = $this->em->getRepository('AppBundle:Species')->findAll();

        // Retourne les espèces
        return $species;
    }

    /**
     * Récupération d'une espèce seule
     *
     * @param $id
     * @return \AppBundle\Entity\Species|null|object
     */
    public function getOneSpecies($id) {
        // Récupération de l'espèce demandé
        $oneSpecies = $this->em->getRepository('AppBundle:Species')->find($id);

        // Retourne l'espèce recherchée
        return $oneSpecies;

    }
}