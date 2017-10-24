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
    public function viewObservationsByUserAction(ObservationManager $observationManager) {
        // Récupération de l'utisateur courant
        $user = $this->getUser();
            
        // Récupération des observations par utilisateurs
        $observations = $observationManager->getObservationsByUser($user);

        return $this->render("default/commonFeatures/myObservations.html.twig", array(
            'observations' => $observations,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/saisie-observation", name="saisieObservation")
     */
    public function addObservationAction(Request $request, ObservationManager $observationManager) {
        // Récupération du formulaire de création d'observation
        $createObservationForm = $observationManager->getObservationForm();

        // Hydration de l'entitée avec les valeurs du formulaire
        $createObservationForm->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $this->render(':default/dashboard/quickAcces:addObservationForm.html.twig');
        }

        // Soumission du formulaire
        if ($createObservationForm->isSubmitted() && $createObservationForm->isValid()) {

            // Récupération de l'utisateur courant
            $user = $this->getUser();

            // Récupération des données
            $observation = $createObservationForm->getData();

            // Enregistrement
            $observationManager->setNewObservation($observation, $user);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('saisieObservation');
        }

        return $this->render(":default:addObservation.html.twig", array(
            'createObservationForm' => $createObservationForm->createView()
        ));
    }
}
