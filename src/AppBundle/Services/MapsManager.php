<?php
/**
 * Created by PhpStorm.
 * User: Adrien
 * Date: 19/10/2017
 * Time: 17:12
 */

namespace AppBundle\Services;


use AppBundle\Entity\Observation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class MapsManager
{
    private $em;
    private $formBuilder;

    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory) {
        $this->em = $entityManager;
        $this->formBuilder = $formFactory;
    }

    public function searchObservationsByReferenceNameForm() {
        // Récupération du formulaire de recherche
        $form = $this->formBuilder->create('AppBundle\Form\Observations\SearchObservationByReferenceNameType');

        // Retourne le formulaire
        return $form;
    }

    public function searchObservationsByVernacularForm() {
        // Récupération du formulaire de recherche
        $form = $this->formBuilder->create('AppBundle\Form\Observations\SearchObservationByVernacularNameType');

        // Retourne le formulaire
        return $form;
    }

    public function searchObservationsByTypeForm() {
        // Récupération du formulaire de recherche
        $form = $this->formBuilder->create('AppBundle\Form\Observations\SearchObservationByTypeType');

        // Retourne le formulaire
        return $form;
    }

    public function searchObservationsByFamilyForm() {
        // Récupération du formulaire de recherche
        $form = $this->formBuilder->create('AppBundle\Form\Observations\SearchObservationByFamilyType');

        // Retourne le formulaire
        return $form;
    }

    public function searchObservationsBySpecies($criteria) {
        // Récupération du résultat de la recherche
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationBySpecies($criteria);

        // Retourne le résultat
        return $result;
    }

    public function searchObservationsByVernacular($criteria) {
        // Récupération du résultat de la recherche
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationByVernacularName($criteria);

        // Retourne le résultat
        return $result;
    }

    public function searchObservationsByType($criteria) {
        // Récupération du résultat de la recherche
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationByType($criteria);

        // Retourne le résultat
        return $result;
    }

    public function searchObservationsByFamily($criteria) {
        // Récupération du résultat de la recherche
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationByFamily($criteria);

        // Retourne le résultat
        return $result;
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

    public function createMarkersXML($searchCriteria = array(Observation::class)) {
        // Création du document XML
        $mapsXMLDoc = new \DOMDocument('1.0', 'utf-8');

        // Création du noeud <urlset>
        $markersNode = $mapsXMLDoc->createElement( 'markers');

        $mapsXMLDoc->appendChild($markersNode);



        // Ajout des URLs dans le document XML
        foreach($searchCriteria as $marker){

            // Création du noeud <url>
            $markerNode = $mapsXMLDoc->createElement('marker');
            $markerNode->setAttribute('user', $marker->getObserver()->getUserName());
            $markerNode->setAttribute('lat', $marker->getLatitude());
            $markerNode->setAttribute('lng', $marker->getLongitude());
            $markerNode->setAttribute('type', 'parking');
            $markersNode->appendChild($markerNode);
        }

        // Ecriture du fichier XML du sitemap
        $mapsXMLPath = 'markers/markers.xml';

        file_put_contents($mapsXMLPath, $mapsXMLDoc->saveXml());
    }
}