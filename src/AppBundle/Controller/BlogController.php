<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /* Affichage des articles */

    /**
     * @Route("/blog", name="blog")
     */
    public function indexBlogAction(BlogManager $blogManager)
    {
        // Récupération de tous les articles
        $posts = $blogManager->getPosts();

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        return $this->render("default/blog/indexBlog.html.twig", array(
            'posts' => $posts,
            'categories' => $categories,
            'threeLastPost' => $threeLastPost
        ));
    }

    /**
     * @Route("/blog/{slugCat}/{slugPost}", name="view-post")
     */
    public function viewPostAction($slugPost, BlogManager $blogManager) {
        // Récupération de l'article via son slug
        $post = $blogManager->getPost($slugPost);

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        return $this->render("default/blog/postBlog.html.twig", array(
            'post' => $post,
            'categories' => $categories,
            'threeLastPost' => $threeLastPost
        ));
    }

    /**
     * @Route("blog/{category}", name="view-posts-by-category")
     */
    public function viewPostsByCategoryAction($category, BlogManager $blogManager) {
        // Récupération du nom de la catégorie à afficher
        $categoryName = $blogManager->getCategory($category)->getName();

        // Récupération des articles lié à la catégorie
        $posts = $blogManager->getPostsByCategory($category);

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        return $this->render("default/blog/categoryBlog.html.twig", array(
            'categoryName' => $categoryName,
            'posts' => $posts,
            'categories' => $categories,
            'threeLastPost' => $threeLastPost
        ));
    }

    /* Gestion des catégories */

    /**
     * @Route("/dasboard/categorie/{slug}/edition/", name="edit_category")
     */
    public function editCategoryAction($slug, BlogManager $blogManager, Request $request) {

        // Récupération du formulaire de modification de la catégorie
        $updateCategoryForm = $blogManager->getFormUpdateCategory($slug);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateCategoryForm->handleRequest($request);

        // Soumission du formulaire
        if ($updateCategoryForm->isSubmitted() && $updateCategoryForm->isValid()) {

            // Récupération de l'entitée Catégory avec les valeurs hydratées
            $category = $updateCategoryForm->getData();

            // Enregistrement de la nouvelle catégorie
            $blogManager->setCategory($category);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/blogManagement/editCategory.html.twig", array(
            'updateCategoryForm' => $updateCategoryForm->createView()
        ));
    }

    /**
     * @Route("/dashboard/categorie/{slug}/confirmation-suppression", name="advice_delete_category")
     */
    public function deleteConfirmationCategoryAction($slug, BlogManager $blogManager) {
        // Récupération des informations lié à la catégorie
        $category = $blogManager->getCategory($slug);

        return $this->render("default/dashboard/blogManagement/deleteCategory.html.twig", array(
            'infoCategory' => $category
        ));
    }

    /**
     * @Route("/dashboard/categorie/{slug}/suppression", name="category_delete")
     */
    public function deleteCategoryAction($slug, BlogManager $blogManager) {
        // Supression de la catégorie
        $blogManager->deleteCategory($slug);

        // Rédirection vers le dashboard
        return $this->redirectToRoute('dashboard');
    }

    /* Gestion des articles */

    /**
     * @Route("/dasboard/article/{slug}/edition/", name="edit_post")
     */
    public function editPostAction($slug, BlogManager $blogManager, Request $request) {

        // Récupération du formulaire de modification de l'article
        $updatePostForm = $blogManager->getUpdatePostForm($slug);

        // Hydration de l'entitée avec les valeurs du formulaire
        $updatePostForm->handleRequest($request);

        // Soumission du formulaire
        if ($updatePostForm->isSubmitted() && $updatePostForm->isValid()) {

            // Récupération de l'entitée Post avec les valeurs hydratées
            $post = $updatePostForm->getData();

            // Enregistrement du nouvel article
            $blogManager->updatePost($post);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/dashboard/blogManagement/editPost.html.twig", array(
            'updatePostForm' => $updatePostForm->createView()
        ));
    }

    /**
     * @Route("/dashboard/article/{slug}/confirmation-suppression", name="advice_delete_post")
     */
    public function deleteConfirmationPostAction($slug, BlogManager $blogManager) {
        // Récupération des informations lié au post
        $post = $blogManager->getPost($slug);

        return $this->render("default/dashboard/blogManagement/deletePost.html.twig", array(
            'infoPost' => $post
        ));
    }

    /**
     * @Route("/dashboard/article/{slug}/suppression", name="post_delete")
     */
    public function deletePostAction($slug, BlogManager $blogManager) {
        // Supression de l'article
        $blogManager->deletePost($slug);

        // Rédirection vers le dashboard
        return $this->redirectToRoute('dashboard');
    }
}