<?php

namespace AppBundle\Controller;

use AppBundle\Services\ObservationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObservationController extends Controller
{
    /**
     * @Route("dashboard/{id}", name="view-observations-by-user")
     */
    public function viewObservationsByUserAction($id, $observation, ObservationManager $observationManager) {
        
        $user = $observationManager->getUser($id);
            
        // Récupération des observations par utilisateurs
        $observations = $observationManager->getObservationsByUser($user);

        // Récupération des epèces liées à une observation
        $specie = $observationManager->getSpecieByObservation($observation);

        return $this->render("default/commonFeatures/myObservations.html.twig", array(
            'user' => $user,
            'observations' => $observations,
            'specie' => $specie,
        ));
    }
}
