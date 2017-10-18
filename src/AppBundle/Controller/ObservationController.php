<?php

namespace AppBundle\Controller;

use AppBundle\Services\ObservationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObservationController extends Controller
{
    /**
     * @Route("dashboard", name="view-observations-by-user")
     */
    public function viewObservationsByUserAction($observation, ObservationManager $observationManager) {

        $user = $this->getUser();
            
        // Récupération des observations par utilisateurs
        $observations = $observationManager->getObservationsByUser($user);
        
        return $this->render("default/commonFeatures/myObservations.html.twig", array(
            'observations' => $observations,
        ));
    }
}
