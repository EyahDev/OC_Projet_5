<?php

namespace AppBundle\Services;

use AppBundle\Entity\Species;
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
     * @param $slug
     * @return \AppBundle\Entity\Species|null|object
     */
    public function getOneSpecies($slug) {
        // Récupération de l'espèce demandé
        $oneSpecies = $this->em->getRepository('AppBundle:Species')->findOneBy(array('slug' => $slug));

        // Retourne l'espèce recherchée
        return $oneSpecies;

    }

    public function getSpeciesDescriptionForm($slug) {
        $species = $this->em->getRepository('AppBundle:Species')->findOneBy(array('slug' => $slug));

        // Récupération du formulaire de modification de description
        $form = $this->formBuilder->create("AppBundle\Form\Species\SpeciesDescriptionType", $species);

        // Retourne le formulaire
        return $form;
    }

    public function setSpeciesDescriptionForm(Species $species) {
        // Sauvegarde de la description de l'espece
        $this->em->persist($species);

        // Enregistrement de la description
        $this->em->flush();
    }
}