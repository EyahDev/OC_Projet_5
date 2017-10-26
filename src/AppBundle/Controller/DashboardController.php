<?php
  
namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use AppBundle\Services\BlogManager;
use AppBundle\Services\CommentManager;
use AppBundle\Services\ContactManager;
use AppBundle\Services\ObservationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dashboard", name="dashboard")
     */

    public function dashboardAction(Request $request, ContactManager $contactManager, BlogManager $blogManager,
                                    ObservationManager $observationManager, CommentManager $commentManager, AccountManager $accountManager)
    {
        /* Utilisateurs */
        $user = $this->getUser();
        $usersList = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();

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
        $createContact = $contactManager->getFormCreateContact();

        // Hydration de l'entitée avec les valeurs du formulaire
        $createContact->handleRequest($request);

        // Soumission du formulaire
        if ($createContact->isSubmitted() && $createContact->isValid()) {

            // Si le formulaire est valide le mail est envoyé
            if($this->sendEmail($createContact->getData())){

                // Rédirection vers le dashboard
                return $this->redirectToRoute('dashboard');

            } else {
                var_dump("Une erreure s'est produite");
            }
        }

        /* Statistiques Utilisateurs */

        // Observations validées pour l'utilisateur classique
        $validatedObservationsByUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getValidatedObservationsByUser($user);

        // Observations refusées pour l'utilisateur classique
        $refusedObservationsByUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getRefusedObservationsByUser($user);

        // Observations refusées par l'utilisateur pro
        $refusedObservationsByValidator = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getRefusedObservationsByValidator($user);

        // Observations refusées par l'utilisateur pro
        $validatedObservationsByValidator = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getValidatedObservationsByValidator($user);

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

        // récupère la liste des questions/réponses
        $faqList = $this->getDoctrine()->getManager()->getRepository('AppBundle:Faq')->findAll();

        /* Mes informations */
        $updateUserNameForm = $accountManager->getFormUpdateName($user);
        $updateUserFirstNameForm = $accountManager->getFormUpdateFirstName($user);
        $updateUserLocationForm = $accountManager->getFormAddLocation($user);
        $updateUserNewsletterForm = $accountManager->getFormUpdateNewsletter($user);
        $updateUserPasswordForm = $accountManager->getFormUpdatePassword();


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
            'contactForm' => $createContact->createView(),
            'faqList' => $faqList,
            'updateUserNameForm' => $updateUserNameForm->createView(),
            'updateUserFirstNameForm' => $updateUserFirstNameForm->createView(),
            'updateUserLocationForm' => $updateUserLocationForm->createView(),
            'updateUserNewsletterForm' => $updateUserNewsletterForm->createView(),
            'updateUserPasswordForm' => $updateUserPasswordForm->createView(),
        ));
    }

    private function sendEmail($data){
        $ContactMail = 'oc_projet_5@laposte.net';
        $ContactPassword = '123456aA';

        $transport = \Swift_SmtpTransport::newInstance('smtp.laposte.net', 465,'ssl')
            ->setUsername($ContactMail)
            ->setPassword($ContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance($data["sujet"])
            ->setFrom($ContactMail)
            ->setTo(array(
                $ContactMail => $ContactMail
            ))
            ->setBody(($data["message"]."<br>ContactMail :".$data["email"]),'text/html');

        return $mailer->send($message);
    }
}
