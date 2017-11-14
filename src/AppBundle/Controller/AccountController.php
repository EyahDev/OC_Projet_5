<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AccountController extends Controller
{
    
    /* Gestion du profil utilisateur */

    /**
     * @param AccountManager $accountManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/user/edition/name", name="edit_user_name")
     * @Method("POST")
     */
    public function editNameAction(AccountManager $accountManager, Request $request) {
        if($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            // Récupération du formulaire
            $updateNameForm = $accountManager->getFormUpdateName($user);

            // Hydration de l'entitée avec les valeurs du formulaire
            $updateNameForm->handleRequest($request);

            // Soumission du formulaire
            if ($updateNameForm->isSubmitted()) {
                // Récupération de l'entitée User avec les valeurs hydratées
                $user = $updateNameForm->getData();
                // récupère le résultat de la validation
                $validation = $accountManager->validateUser($user);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement
                $accountManager->updateUser($user);

                // Envoie le message de confirmation de mise à jour
                return new Response('Nom mis à jour');
            }

            throw new \Exception('Une erreur est survenue');
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param AccountManager $accountManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/user/edition/firstname", name="edit_user_firstname")
     * @Method("POST")
     */
    public function editFirstNameAction(AccountManager $accountManager, Request $request) {
        if($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            // Récupération du formulaire
            $updateFirstNameForm = $accountManager->getFormUpdateFirstName($user);

            // Hydration de l'entitée avec les valeurs du formulaire
            $updateFirstNameForm->handleRequest($request);

            // Soumission du formulaire
            if ($updateFirstNameForm->isSubmitted()) {
                // Récupération de l'entitée User avec les valeurs hydratées
                $user = $updateFirstNameForm->getData();
                // récupère le résultat de la validation
                $validation = $accountManager->validateUser($user);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement
                $accountManager->updateUser($user);

                // Envoie le message de confirmation de mise à jour
                return new Response('Prénom mis à jour');
            }

            throw new \Exception('Une erreur est survenue');
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param AccountManager $accountManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/user/edition/location", name="edit_user_location")
     * @Method("POST")
     */
    public function editLocationAction(AccountManager $accountManager, Request $request) {
        if($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            // Récupération du formulaire
            $addLocationForm = $accountManager->getFormAddLocation($user);

            // Hydration de l'entitée avec les valeurs du formulaire
            $addLocationForm->handleRequest($request);

            // Soumission du formulaire
            if ($addLocationForm->isSubmitted()) {

                // Récupération de l'entitée User avec les valeurs hydratées
                $user = $addLocationForm->getData();
                // récupère le résultat de la validation
                $validation = $accountManager->validateUser($user);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement
                $accountManager->updateUser($user);

                // Envoie le message de confirmation de mise à jour
                return new Response('Adresse mis à jour');
            }
            throw new \Exception('Une erreur est survenue');
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param AccountManager $accountManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/user/edition/avatar", name="edit_user_avatar")
     * @Method("POST")
     */
    public function editAvatarAction(AccountManager $accountManager, Request $request) {
        if($request->isXmlHttpRequest()) {
            $user = $this->getUser();

            // Récupération du formulaire pour la modification de l'avatar
            $avatarForm = $accountManager->getFormUpdateAvatar($user);

            // Récupération du fichier existant
            $existingFile = $avatarForm->getData()->getAvatarPath();

            // Hydratation des valeurs
            $avatarForm->handleRequest($request);

            // Soumission du formulaire
            if ($avatarForm->isSubmitted()) {
                // Récupération des données
                $newAvatar = $avatarForm->getData();
                // récupère le résultat de la validation
                $validation = $accountManager->validateUser($newAvatar);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }

                // mise à jour de l'avatar
                $accountManager->updateAvatar($newAvatar, $existingFile);

                // Renvoie l'avatar et le formulaire pour le remplacement en js
                return $this->render('default/dashboard/commonFeatures/myAccount/avatarSection.html.twig', array(
                    'avatarForm' => $avatarForm->createView()
                ));
            }
            throw new \Exception('Une erreur est survenue');
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param AccountManager $accountManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/user/edition/newsletter", name="edit_user_newsletter")
     * @Method("POST")
     */
    public function editNewsletterAction(AccountManager $accountManager, Request $request) {
        if($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            // Récupération du formulaire
            $updateNewsletterForm = $accountManager->getFormUpdateNewsletter($user);

            // Hydration de l'entitée avec les valeurs du formulaire
            $updateNewsletterForm->handleRequest($request);

            // Soumission du formulaire
            if ($updateNewsletterForm->isSubmitted()) {

                // Récupération de l'entitée User avec les valeurs hydratées
                $user = $updateNewsletterForm->getData();
                // récupère le résultat de la validation
                $validation = $accountManager->validateUser($user);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement
                $accountManager->updateUser($user);

                // Envoie le message de confirmation de mise à jour
                return new Response('inscription à la newsletter mis à jour');
            }
            throw new \Exception('Une erreur est survenue');
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }

    /**
     * @param AccountManager $accountManager
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/dashboard/user/edition/mot-de-passe", name="edit_user_password")
     * @Method("POST")
     */
    public function editPasswordAction( AccountManager $accountManager, Request $request) {

        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            // Récupération du formulaire
            $updatePasswordForm = $accountManager->getFormUpdatePassword();

            // Hydration de l'entitée avec les valeurs du formulaire
            $updatePasswordForm->handleRequest($request);

            // Soumission du formulaire
            if ($updatePasswordForm->isSubmitted()) {
                // stocke les données venant du formulaire
                $data = $updatePasswordForm->getData();
                // valide les données
                $validation = $accountManager->validatePassword($data, $user);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement
                $accountManager->updatePassword($user, $data['newPassword']);

                // Envoie le message de confirmation de mise à jour
                return new Response('Mot de passe modifié');
            }
            throw new \Exception('Une erreur est survenue');
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
    }
}
