<?php

namespace AppBundle\Services;

use AppBundle\Entity\Species;
use AppBundle\Form\Type\Species\SpeciesDescriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class SpeciesManager
{
    private $formBuilder;
    private $em;

    /**
     * SpeciesManager constructor.
     * @param FormFactoryInterface $formBuilder
     * @param EntityManagerInterface $em
     */
    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
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
        $oneSpecies = $this->em->getRepository('AppBundle:Species')->findOneBy(array('slug' => $slug, ));

        // Retourne l'espèce recherchée
        return $oneSpecies;

    }

    public function getSpeciesDescriptionForm($slug) {
        $species = $this->em->getRepository('AppBundle:Species')->findOneBy(array('slug' => $slug));

        // Récupération du formulaire de modification de description
        $form = $this->formBuilder->create(SpeciesDescriptionType::class, $species);

        // Retourne le formulaire
        return $form;
    }

    public function setSpeciesDescriptionForm(Species $species) {
        // Sauvegarde de la description de l'espece
        $this->em->persist($species);

        // Enregistrement de la description
        $this->em->flush();
    }

    public function getPhotosPath($slug) {
        // Récupération des observations
        $observations = $this->getOneSpecies($slug)->getObservations();

        $photos = array();

        $noPhotoCount = 0;

        foreach ($observations as $observation) {
            if (strpos($observation->getPhotoPath(), 'uploads/observations_files/') === false) {
                $noPhotoCount++;
            } else {
                array_push($photos, $observation->getPhotoPath());
            }
        }

        if ($noPhotoCount === count($observations)) {
            return false;
        } else {
            return $photos;
        }
    }
}
