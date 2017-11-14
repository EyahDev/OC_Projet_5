<?php

namespace AppBundle\Controller;

use AppBundle\Services\ObservationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ObservationController extends Controller
{
    /**
     * @param Request $request
     * @param ObservationManager $observationManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     *
     * @Route("/saisie-observation", name="saisieObservation")
     * @Method({"GET", "POST"})
     */
    public function addObservationAction(Request $request, ObservationManager $observationManager) {
        // Test si l'utilisateur est anonyme et si oui redirige vers une page 403
        if($this->getUser() === null) {
            throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
        }
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

    /**
     * @param $id
     * @param ObservationManager $observationManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/observation/{id}/modification", name="modify-observation")
     * @Method({"GET", "POST"})
     */
    public function modifyObservationAction($id, ObservationManager $observationManager, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            // récupère l'observation
            $observation = $observationManager->getObservation($id);
            // Récupération du formulaire de modificaiton d'observation par l'utilisateur propriétaire
            $modifyObservationForm = $observationManager->getObservationForModifyForm($id);

            // Récupération des fichiers déjà présent
            $existingFile = $modifyObservationForm->getData()->getPhotoPath();

            // Hydratation de l'entitée avec les valeurs du formulaire
            $modifyObservationForm->handleRequest($request);

            // Soumission du formulaire
            if ($modifyObservationForm->isSubmitted()) {
                // Récupération des valeurs du formulaire
                $updatedObservation = $modifyObservationForm->getData();
                // Valide l'observation et récupère les erreurs de formulaire si il y en a
                $validation = $observationManager->validateObservation($updatedObservation);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }

                // Mise à jour du de l'observation
                $observationManager->setUpdatedObservation($updatedObservation, $existingFile);

                // renvoie la ligne de tableau pour l'affichage en JS
                return new Response('Observations : édition ok');
            }

            return $this->render("default/dashboard/commonFeatures/myObservations/modifyObservationForm.html.twig", array(
                'modifyObservationForm' => $modifyObservationForm->createView(),
                'observation' => $observation
            ));
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param $id
     * @param ObservationManager $observationManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/dashboard/observation/{id}/detail", name="observation-detail")
     * @Method({"GET", "POST"})
     */
    public function observationDetailAction($id, ObservationManager $observationManager, Request $request) {
        // Récupération de l'utilisateur courant
        $user = $this->getUser();

        // Récupération du formulaire pour la visibilité et modification du détail et la validation de l'observation
        $observationDetailForm = $observationManager->getObservationForValidationForm($id);

        // Hydratation de l'entitée avec les valeurs du formulaire
        $observationDetailForm->handleRequest($request);

        if ($observationDetailForm->isSubmitted() && $observationDetailForm->isValid()) {
            // Récupération des valeurs du formulaire
            $observation = $observationDetailForm->getData();

            // Vérification du bouton de soumission
            if ($observationDetailForm->get('Refuser')->isClicked()) {
                // Refus de l'observation
                $observationManager->setRefusedObservation($observation, $user);

                // Redirection version le dashboard
                return $this->redirectToRoute('dashboard');

            } elseif ($observationDetailForm->get('Valider')->isClicked()) {
                // Acceptation de l'observation
                $observationManager->setAcceptedObservation($observation, $user);
                // Redirection version le dashboard
                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render("default/dashboard/ObservationManagement/detailObservation.html.twig", array(
            'observationDetailForm' => $observationDetailForm->createView()
        ));
    }

    /**
     * @param Request $request
     * @param ObservationManager $observationManager
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/myObservations/pagination", name="pagination_my_observations")
     * @Method("GET")
     */
    public function paginationMyObservationsAction(Request $request, ObservationManager $observationManager)
    {
        if($request->isXmlHttpRequest()) {
            $currentUserObservations = $observationManager->getCurrentUserPaginatedObservationsList($this->getUser());
            return $this->render('default/dashboard/commonFeatures/myObservations/paginatedTable.html.twig', array(
                'currentUserObservations' => $currentUserObservations
            ));
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param Request $request
     * @param ObservationManager $observationManager
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/observationsValidation/pagination", name="pagination_observations_validation")
     * @Method("GET")
     */
    public function paginationObservationsValidationAction(Request $request, ObservationManager $observationManager)
    {
        if($request->isXmlHttpRequest()) {
            $observations = $observationManager->getObservationsUnvalidated();
            return $this->render('default/dashboard/commonFeatures/ObservationsValidation/paginatedTable.html.twig', array(
                'observations' => $observations
            ));
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }
}
