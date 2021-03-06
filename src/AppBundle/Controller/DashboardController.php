<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use AppBundle\Services\BlogManager;
use AppBundle\Services\CommentManager;
use AppBundle\Services\ContactManager;
use AppBundle\Services\FAQManager;
use AppBundle\Services\ObservationManager;
use AppBundle\Services\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DashboardController extends Controller
{
    /**
     * @param Request $request
     * @param ContactManager $contactManager
     * @param BlogManager $blogManager
     * @param ObservationManager $observationManager
     * @param CommentManager $commentManager
     * @param AccountManager $accountManager
     * @param UserManager $userManager
     * @param FAQManager $FAQManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/dashboard", name="dashboard")
     * @Method({"GET", "POST"})
     */
    public function dashboardAction(Request $request, ContactManager $contactManager, BlogManager $blogManager,
                                    ObservationManager $observationManager, CommentManager $commentManager,
                                    AccountManager $accountManager, UserManager $userManager, FAQManager $FAQManager)
    {


        /* Utilisateurs */
        $user = $this->getUser();


        // Test si l'utilisateur est anonyme et redirige vers une page 403
        if($user === null) {
            throw new \Exception("Vous ne pouvez pas accéder à cette page", 403);
        }


        /* Accès rapide */

        // Récupération du formulaire de saisie d'observation
        $createObservation = $observationManager->getObservationForm();

        // Hydratation de l'entitée avec les valeurs du formulaire
        $createObservation->handleRequest($request);

        // Soumission du formulaire
        if ($createObservation->isSubmitted() && $createObservation->isValid()) {

            // Récupération de l'entitée Observation avec les valeurs hydratées
            $observation = $createObservation->getData();

            // Enregistrement de la nouvelle observation
            $observationManager->setNewObservation($observation, $user);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        /* Nous écrire */

        // Récupération du formulaire de contact
        $createContactUs = $contactManager->getFormCreateContactUs();


        /* Statistiques Utilisateurs */

        // Observations validées pour l'utilisateur classique
        $validatedObservationsByUser = $observationManager->validatedObservationsByUser($user);

        // Observations refusées pour l'utilisateur classique

        $refusedObservationsByUser = $observationManager->refusedObservationsByUser($user);


        // Observations refusées par l'utilisateur pro
        $refusedObservationsByValidator = $observationManager->refusedObservationsByValidator($user);

        // Observations validées par l'utilisateur pro
        $validatedObservationsByValidator = $observationManager->validatedObservationsByValidator($user);        

        /* Observations */

        // Récupération des observations non validées
        $observations = $observationManager->getObservationsUnvalidated();

        /* Catégories */

        // Récupération de la liste des catégories
        $categoriesList = $blogManager->getPaginatedCategoriesList();

        // Récupération du formulaire de création d'une nouvelle catégorie
        $createCategory = $blogManager->getFormCreateCategory();


        /* Articles */

        // Récupération de la liste des articles
        $postsList = $blogManager->getPaginatedPostList();

        // Récupération du formulaire de création d'un article
        $createPost = $blogManager->getFormCreatePost();

        // Récupération du formulaire de création rapide d'une catégorie pour la partie article
        $createCategoryQuickly = $blogManager->getFormCreateQuicklyCategory();


        /* Commentaires */

        // Récupération des commentaires signalés
        $commentsFlagged = $commentManager->getPaginatedCommentsFlaggedList();


        /* Gestion de la FAQ */
        $paginationFaq = $FAQManager->getPaginatedFaqList();


        /* Mes informations */

        // Récupération du formulaire pour la modification du nom
        $updateUserNameForm = $accountManager->getFormUpdateName($user);
        // Récupération du formulaire pour la modification du prénom
        $updateUserFirstNameForm = $accountManager->getFormUpdateFirstName($user);
        // Récupération du formulaire pour la modification de l'adresse
        $updateUserLocationForm = $accountManager->getFormAddLocation($user);
        // Récupération du formulaire pour la modification de l'inscription à la newsletter
        $updateUserNewsletterForm = $accountManager->getFormUpdateNewsletter($user);
        // Récupération du formulaire pour la modification du mot de passe
        $updateUserPasswordForm = $accountManager->getFormUpdatePassword();
        // Récupération du formulaire pour la modification de l'avatar
        $avatarForm = $accountManager->getFormUpdateAvatar($user);

        /* Mes observations*/
        $currentUserObservations = $observationManager->getObservationsByUser($user);

        /* Gestion des utilisateurs*/
        $usersList = $userManager->getPaginatedUsersList($user->getId());

        return $this->render("default/dashboard.html.twig", array(
            'createCategoryForm' => $createCategory->createView(),
            'categoriesList' => $categoriesList,
            'createCategoryQuickly' => $createCategoryQuickly->createView(),
            'createPostForm' => $createPost->createView(),
            'postsList' => $postsList,
            'usersList' => $usersList,
            'observations' => $observations,
            'commentsFlagged' => $commentsFlagged,
            'validatedObservationsByUser' => $validatedObservationsByUser,
            'refusedObservationsByUser' => $refusedObservationsByUser,
            'refusedObservationsByValidator' => $refusedObservationsByValidator,
            'validatedObservationsByValidator' => $validatedObservationsByValidator,
            'createObservationForm' => $createObservation->createView(),
            'paginationFaq' => $paginationFaq,
            'contactForm' => $createContactUs->createView(),
            'updateUserNameForm' => $updateUserNameForm->createView(),
            'updateUserFirstNameForm' => $updateUserFirstNameForm->createView(),
            'updateUserLocationForm' => $updateUserLocationForm->createView(),
            'updateUserNewsletterForm' => $updateUserNewsletterForm->createView(),
            'updateUserPasswordForm' => $updateUserPasswordForm->createView(),
            'avatarForm' => $avatarForm->createView(),
            'currentUserObservations' => $currentUserObservations,

        ));
    }
}
