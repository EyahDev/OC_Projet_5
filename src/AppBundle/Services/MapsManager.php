<?php

namespace AppBundle\Services;

use AppBundle\Form\Type\Observations\SearchObservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class MapsManager
{
    private $em;
    private $formBuilder;
    private $observationManager;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, ObservationManager $observationManager) {
        $this->em = $entityManager;
        $this->formBuilder = $formFactory;
        $this->observationManager = $observationManager;
    }

    public function searchObservationsForm() {
        // Récupération du formulaire de recherche
        $form = $this->formBuilder->create(SearchObservationType::class);

        // Retourne le formulaire
        return $form;
    }

    public function searchObservations($criteria) {
        // Creation d'un tableau de résultat
        $results = array();

        // Recherche par nom de reference
        if ($criteria['reference'] != null) {
            // Récupération de la recherche
            $queryReference = $this->observationManager->getObservationsByReferenceName($criteria);

            // Vérification si le résultat n'est pas vide
            if (!empty($queryReference)) {
                array_push($results, $queryReference);
            };
        }

        // Recherche par nom commun
        if ($criteria['vernacular'] != null) {
            // Récupération de la recherche
            $queryVernacular = $this->observationManager->getObservationsByVernacular($criteria);

            // Vérification si le résultat n'est pas vide
            if (!empty($queryVernacular)) {
                array_push($results, $queryVernacular);
            };
        }

        // Recherche par ordre
        if ($criteria['type'] != null) {
            // Récupération de la recherche
            $queryType = $this->observationManager->getObservationsByType($criteria);

            // Vérification si le résultat n'est pas vide
            if (!empty($queryType)) {
                array_push($results, $queryType);
            };
        }

        // Recherche par famille
        if ($criteria['family'] != null) {
            // Récupération de la recherche
            $queryFamily = $this->observationManager->getObservationsByFamily($criteria);

            // Vérification si le résultat n'est pas vide
            if (!empty($queryFamily)) {
                array_push($results, $queryFamily);
            };
        }

        return $results;
    }

    public function resetMarkersXML() {
        // Création du document XML
        $mapsXMLDoc = new \DOMDocument('1.0', 'utf-8');

        // Création du noeud <urlset>
        $markersNode = $mapsXMLDoc->createElement( 'markers');

        // Ecriture dans le fichier
        $mapsXMLDoc->appendChild($markersNode);

        $markerNode = $mapsXMLDoc->createElement('marker');

        $markersNode->appendChild($markerNode);

        // Ecriture du fichier XML du sitemap
        $mapsXMLPath = 'markers/markers.xml';

        file_put_contents($mapsXMLPath, $mapsXMLDoc->saveXml());
    }

    public function createMarkersXML($searchCriteria = array()) {
        // Création du document XML
        $mapsXMLDoc = new \DOMDocument('1.0', 'utf-8');

        // Création du noeud <urlset>
        $markersNode = $mapsXMLDoc->createElement( 'markers');

        $mapsXMLDoc->appendChild($markersNode);

        // Ajout des URLs dans le document XML
        foreach($searchCriteria as $results){
            foreach ($results as $marker) {
                // Création du noeud <url>
                $markerNode = $mapsXMLDoc->createElement('marker');
                $markerNode->setAttribute('user', $marker->getObserver()->getUserName());
                $markerNode->setAttribute('date', $marker->getObservationDate()->format('d/m/Y à H:i'));
                $markerNode->setAttribute('reference', $marker->getSpecies()->getReferenceName());
                $markerNode->setAttribute('slug', $marker->getSpecies()->getSlug());
                $markerNode->setAttribute('vernacular', $marker->getSpecies()->getVernacularName());
                $markerNode->setAttribute('birdNumber', $marker->getBirdNumber());
                $markerNode->setAttribute('eggsNumber', $marker->getEggsNumber());
                $markerNode->setAttribute('photo', $marker->getPhotoPath());
                $markerNode->setAttribute('lat', $marker->getLatitude());
                $markerNode->setAttribute('lng', $marker->getLongitude());
                $markerNode->setAttribute('seeToo', $marker->getSeeToo());
                $markerNode->setAttribute('type', 'bird');
                $markersNode->appendChild($markerNode);
            }
        }

        // Ecriture du fichier XML du sitemap
        $mapsXMLPath = 'markers/markers.xml';

        file_put_contents($mapsXMLPath, $mapsXMLDoc->saveXml());
    }
}
