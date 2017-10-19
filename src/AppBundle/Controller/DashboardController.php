<?php
  
namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
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
    public function dashboardAction(Request $request, BlogManager $blogManager, ObservationManager $observationManager)
    {
        /* Utilisateurs */
        $user = $this->getUser();
        $usersList = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();

        
        /* Observations validées pour l'utilisateur classique */
        $validatedObservationsByUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getValidatedObservationsByUser($user);

        /* Observations refusées pour l'utilisateur classique */
        $refusedObservationsByUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getRefusedObservationsByUser($user);

        /* Observations refusées par l'utilisateur pro */
        $refusedObservationsByValidator = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getRefusedObservationsByValidator($user);

        /* Observations refusées par l'utilisateur pro */
        $validatedObservationsByValidator = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getValidatedObservationsByValidator($user);

        /* Observations */
        $observations = $observationManager->getObservations();

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

        // Récupération du formulaire de création d'une catégorie
        $createPost = $blogManager->getFormCreatePost();

        // Hydratation de l'entitée des valeurs du formulaire
        $createPost->handleRequest($request);

        // Soumission du formulaire
        if ($createPost->isSubmitted() && $createPost->isValid()) {

            // Récupération de l'entitée Post avec les valeurs hydratées
            $post = $createPost->getData();

            // Enregistrement du nouvel article
            $blogManager->setPost($post, $user);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard.html.twig", array(
            'createCategoryForm' => $createCategory->createView(),
            'categoriesList' => $categoriesList,
            'createPostForm' => $createPost->createView(),
            'postsList' => $postsList,
            'usersList' => $usersList,
            'observations' => $observations,
            'validatedObservationsByUser' => $validatedObservationsByUser,
            'refusedObservationsByUser' => $refusedObservationsByUser,
            'refusedObservationsByValidator' => $refusedObservationsByValidator,
            'validatedObservationsByValidator' => $validatedObservationsByValidator
            
        ));
    }
}
