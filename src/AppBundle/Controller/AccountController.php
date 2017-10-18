<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Services\AccountManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends Controller
{
    
    /* Gestion du profil utilisateur */

    /**
 * @Route("/dasboard/user/{id}/edition/name", name="edit_user_name")
 */
    public function editNameAction($id, AccountManager $accountManager, Request $request) {

        $em = $this->getDoctrine()->getManager();
        // Récupération du formulaire
        $updateNameForm = $accountManager->getFormUpdateName($id);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateNameForm->handleRequest($request);

        // Soumission du formulaire
        if ($updateNameForm->isSubmitted() && $updateNameForm->isValid()) {

            // Récupération de l'entitée User avec les valeurs hydratées
            $user = $updateNameForm->getData();

            // Enregistrement
            $em->persist($user);
            $em->flush();

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/commonFeatures/myAccount/editUserName.html.twig", array(
            'updateNameForm' => $updateNameForm->createView(),
        ));
    }

    /**
     * @Route("/dasboard/user/{id}/edition/firstname", name="edit_user_firstname")
     */
    public function editFirstNameAction($id, AccountManager $accountManager, Request $request) {

        $em = $this->getDoctrine()->getManager();
        // Récupération du formulaire
        $updateFirstNameForm = $accountManager->getFormUpdateFirstName($id);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateFirstNameForm->handleRequest($request);

        // Soumission du formulaire
        if ($updateFirstNameForm->isSubmitted() && $updateFirstNameForm->isValid()) {

            // Récupération de l'entitée User avec les valeurs hydratées
            $user = $updateFirstNameForm->getData();

            // Enregistrement
            $em->persist($user);
            $em->flush();

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/commonFeatures/myAccount/editUserFirstName.html.twig", array(
            'updateFirstNameForm' => $updateFirstNameForm->createView(),
        ));
    }

    /**
     * @Route("/dasboard/user/{id}/edition/location", name="edit_user_location")
     */
    public function editLocationAction($id, AccountManager $accountManager, Request $request) {

        $em = $this->getDoctrine()->getManager();
        // Récupération du formulaire
        $addLocationForm = $accountManager->getFormAddLocation($id);

        // Hydration de l'entitée avec les valeurs du formulaire
        $addLocationForm->handleRequest($request);

        // Soumission du formulaire
        if ($addLocationForm->isSubmitted() && $addLocationForm->isValid()) {

            // Récupération de l'entitée User avec les valeurs hydratées
            $user = $addLocationForm->getData();

            // Enregistrement
            $em->persist($user);
            $em->flush();

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/commonFeatures/myAccount/editUserLocation.html.twig", array(
            'addLocationForm' => $addLocationForm->createView(),
        ));
    }

    /**
     * @Route("/dasboard/user/{id}/edition/newsletter", name="edit_user_newsletter")
     */
    public function editNewsletterAction($id, AccountManager $accountManager, Request $request) {

        $em = $this->getDoctrine()->getManager();
        // Récupération du formulaire
        $updateNewsletterForm = $accountManager->getFormUpdateNewsletter($id);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateNewsletterForm->handleRequest($request);

        // Soumission du formulaire
        if ($updateNewsletterForm->isSubmitted() && $updateNewsletterForm->isValid()) {

            // Récupération de l'entitée User avec les valeurs hydratées
            $user = $updateNewsletterForm->getData();

            // Enregistrement
            $em->persist($user);
            $em->flush();

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/commonFeatures/myAccount/editUserNewsletter.html.twig", array(
            'updateNewsletterForm' => $updateNewsletterForm->createView(),
        ));
    }
}
