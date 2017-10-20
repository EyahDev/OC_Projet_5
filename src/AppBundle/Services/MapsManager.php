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

    public function searchObservationsForm() {
        // Récupération du formulaire de recherche
        $form = $this->formBuilder->create('AppBundle\Form\Observations\SearchObservationForm');

        // Retourne le formulaire
        return $form;
    }

    public function searchObservations($criteria) {

        $result = $this->em->getRepository('AppBundle:Observation')->getObservationByCriteria($criteria);

        dump($result);
        return $result;

    }

    public function createMarkersXML($searchCriteria = array(Observation::class)) {

        dump($searchCriteria);

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