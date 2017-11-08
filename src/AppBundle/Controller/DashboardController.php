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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dashboard", name="dashboard")
     */

    public function dashboardAction(Request $request, ContactManager $contactManager, BlogManager $blogManager,
                                    ObservationManager $observationManager, CommentManager $commentManager,
                                    AccountManager $accountManager, UserManager $userManager, FAQManager $FAQManager)
    {


        /* Utilisateurs */
        $user = $this->getUser();


        // Test si l'utilisateur est anonyme et redirige vers une page 403
        if($user === null) {
            throw new AccessDeniedHttpException('Vous ne pouvez pas accéder à cette page');
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

        // Hydration de l'entitée avec les valeurs du formulaire
        $createContactUs->handleRequest($request);

        // Soumission du formulaire
        if ($createContactUs->isSubmitted() && $createContactUs->isValid()) {
            // Récupération des données du formulaire
            $data = $createContactUs->getData();

            // Préparation de l'email et envoi
            $contactManager->sendMailUser($data);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

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

        // Récupération des observations de l'utilisateur
        $observations = $observationManager->getObservationsUnvalidated();

        /* Catégories */

        // Récupération de la liste des catégories
        $categoriesList = $blogManager->getCategories();

        // Récupération du formulaire de création d'une nouvelle catégorie
        $createCategory = $blogManager->getFormCreateCategory();

        // Hydration de l'entitée avec les valeurs du formulaire
        $createCategory->handleRequest($request);

        // Soumission du formulaire
        if ($createCategory->isSubmitted() && $createCategory->isValid()) {

            // Récupération de l'entitée Category avec les valeurs hydratées
            $category = $createCategory->getData();

            // Enregistrement de la nouvelle catégorie
            $blogManager->setCategory($category);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        /* Articles */

        // Récupération de la liste des articles
        $postsList = $blogManager->getPosts();

        // Récupération du formulaire de création d'un article
        $createPost = $blogManager->getFormCreatePost();

        // Récupération du formulaire de création rapide d'une catégorie pour la partie article
        $createCategoryQuickly = $blogManager->getFormCreateQuicklyCategory();

        // Hydratation des entitées des valeurs du formulaire
        $createPost->handleRequest($request);
        $createCategoryQuickly->handleRequest($request);

        // Soumission du formulaire
        if ($createPost->isSubmitted() && $createPost->isValid()) {

            // Récupération de l'entitée Post avec les valeurs hydratées
            $post = $createPost->getData();

            // Enregistrement du nouvel article
            $blogManager->setPost($post, $user);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        // Soumission du formulaire
        if ($createCategoryQuickly->isSubmitted() && $createCategoryQuickly->isValid()) {

            // Récupération de l'entitée Post avec les valeurs hydratées
            $category = $createCategoryQuickly->getData();

            // Enregistrement du nouvel article
            $blogManager->setCategory($category);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        /* Commentaires */

        // Récupération des commentaires signalés
        $commentsFlagged = $commentManager->getCommentsFlagged();


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
        $currentUserObservations = $observationManager->getCurrentUserPaginatedObservationsList($user);

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
