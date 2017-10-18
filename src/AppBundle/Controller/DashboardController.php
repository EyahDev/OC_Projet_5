<?php
  
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Observation;
use AppBundle\Services\BlogManager;
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
    public function dashboardAction(Request $request, BlogManager $blogManager, SessionInterface $session)
    {
        /* Utilisateurs */
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        /* Observations */
        $observations = $this->getDoctrine()->getRepository(Observation::class)->findAll();

        /* Espèces */
        $species = $this->getDoctrine()->getManager()->getRepository('AppBundle:Species')->findAll();

        /* Catégories */

        // Récupération de la liste des catégories
        $categoriesList = $blogManager->getCategories();

        // Récupération du formulaire de création d'une nouvelle catégorie
        $createCategory = $blogManager->getFormCreateCategory();

        // Hydration de l'entitée avec les valeurs du formulaire
        $createCategory->handleRequest($request);

        // Soumission du formulaire
        if ($createCategory->isSubmitted() && $createCategory->isValid()) {

            // Récupération de l'entitée Catégory avec les valeurs hydratées
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
            $post = $createPost->getData();

            // Enregistrement du nouvel article
            $blogManager->setPost($post);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard.html.twig", array(
            'createCategoryForm' => $createCategory->createView(),
            'categoriesList' => $categoriesList,
            'createPostForm' => $createPost->createView(),
            'postsList' => $postsList,
            'users' => $users,
            'observations' => $observations,
            'species' => $species
        ));
    }
}
