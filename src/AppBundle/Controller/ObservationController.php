<?php

namespace AppBundle\Controller;

use AppBundle\Services\ObservationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ObservationController extends Controller
{
    /**
     * @Route("dashboard", name="view-observations-by-user")
     */
    public function viewObservationsByUserAction(ObservationManager $observationManager) {
        // Récupération de l'utisateur courant
        $user = $this->getUser();
            
        // Récupération des observations par utilisateurs
        $observations = $observationManager->getObservationsByUser($user);
        
        return $this->render("default/commonFeatures/myObservations.html.twig", array(
            'observations' => $observations,
        ));
    }
}
