<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PostController extends Controller
{
    /* Affichage des articles */

    /**
     * @param BlogManager $blogManager
     * @return Response
     *
     * @Route("/blog", name="blog")
     * @Method("GET")
     */
    public function indexBlogAction(BlogManager $blogManager)
    {
        // Récupération de tous les articles
        $posts = $blogManager->getPosts();

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        /* Gestion de la pagination des articles */
        $paginationPosts = $blogManager->getPaginatedPostList();

        return $this->render("default/blog/indexBlog.html.twig", array(
            'posts' => $posts,
            'categories' => $categories,
            'threeLastPost' => $threeLastPost,
            'paginationPosts' => $paginationPosts
        ));
    }

    /**
     * @param $slugPost
     * @param $slugCat
     * @param BlogManager $blogManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/blog/{slugCat}/{slugPost}", name="view-post")
     * @Method({"GET", "POST"})
     */
    public function viewPostAction($slugPost, $slugCat, BlogManager $blogManager, Request $request) {
        // Récupération de l'article via son slug
        $post = $blogManager->getPost($slugPost);

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        // Récupération de la liste des commentaire
        $commentsList = $blogManager->getCommentsByPost($post->getId());

        // Récupération du formulaire de rédaction d'un nouveau commentaire
        $commentForm = $blogManager->getCommentForm();

        // Hydration de l'entitée avec les valeurs du formulaire
        $commentForm->handleRequest($request);

        // Soumission du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            // Récupération de l'auteur du message
            $user = $this->getUser();

            // Récupération de l'entitée Catégory avec les valeurs hydratées
            $comment = $commentForm->getData();

            // Enregistrement de la nouvelle catégorie
            $blogManager->setComment($comment, $post, $user);

            // Rédirection vers le dashboard
            return $this->redirectToRoute('view-post', array(
                'slugCat' => $slugCat,
                'slugPost' => $slugPost
            ));
        }

        return $this->render("default/blog/postBlog.html.twig", array(
            'post' => $post,
            'categories' => $categories,
            'threeLastPost' => $threeLastPost,
            'commentForm' => $commentForm->createView(),
            'commentsList' => $commentsList
        ));
    }

    /**
     * @param $categorySlug
     * @param BlogManager $blogManager
     * @return Response
     *
     * @Route("blog/{categorySlug}", name="view-posts-by-category")
     * @Method("GET")
     */
    public function viewPostsByCategoryAction($categorySlug, BlogManager $blogManager) {
        // Récupération du nom de la catégorie à afficher
        $cat = $blogManager->getCategory($categorySlug);

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        /* Gestion de la pagination */
        $paginationPostsCategory = $blogManager->getPaginatedPostsCategoryList($cat);

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        return $this->render("default/blog/categoryBlog.html.twig", array(
            'category' => $cat,
            'categories' => $categories,
            'paginationPostsCategory' => $paginationPostsCategory,
            'threeLastPost' => $threeLastPost
        ));
    }

    /* Gestion des articles */

    /**
     * @param Request $request
     * @param BlogManager $blogManager
     * @return Response
     *
     * @Route("/dashboard/rediger-article/rechargement", name="reload_write_post")
     * @Method("GET")
     */
    public function reloadWritePostAction(Request $request, BlogManager $blogManager)
    {
        if($request->isXmlHttpRequest()) {
            $createCategoryQuickly = $blogManager->getFormCreateQuicklyCategory();
            $createPostForm = $blogManager->getFormCreatePost();
            return $this->render("default/dashboard/blogManagement/writePost/writePost.html.twig", array(
                'createCategoryQuickly' => $createCategoryQuickly->createView(),
                'createPostForm' => $createPostForm->createView(),
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @param BlogManager $blogManager
     * @return Response
     *
     * @Route("/dashboard/rediger-article", name="write_post")
     * @Method("POST")
     */
    public function writePostAction(Request $request, BlogManager $blogManager)
    {
        if($request->isXmlHttpRequest()) {
            // Récupération du formulaire de création d'un article
            $createPost = $blogManager->getFormCreatePost();

            // Récupération de l'utilisateur courant
            $user = $this->getUser();

            // Hydratation du formulaire
            $createPost->handleRequest($request);

            // Soumission du formulaire
            if ($createPost->isSubmitted()) {

                // Récupération de l'entitée Post avec les valeurs hydratées
                $post = $createPost->getData();

                // récupère le résultat de la validation
                $validation = $blogManager->validatePost($post);

                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }

                // Enregistrement du nouvel article
                $blogManager->setPost($post, $user);

                // Renvoie un message de confirmation
                return new Response("Article ajouté");
            }
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }


    /**
     * @param $slug
     * @param BlogManager $blogManager
     * @param Request $request
     * @return Response
     *
     * @Route("/dashboard/article/{slug}/edition/", name="edit_post")
     * @Method({"GET", "POST"})
     */
    public function editPostAction($slug, BlogManager $blogManager, Request $request) {

        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // Récupération du formulaire de modification de l'article
            $updatePostForm = $blogManager->getUpdatePostForm($slug);

            // récupère la categorie
            $post = $blogManager->getPost($slug);

            // Récupération du fichier d'origine
            $existingFile = $updatePostForm->getData()->getImagePath();

            // Hydration de l'entitée avec les valeurs du formulaire
            $updatePostForm->handleRequest($request);

            // teste si la requete est en POST et si les données sont valides
            if($updatePostForm->isSubmitted()) {
                // Récupération de l'entitée Post avec les valeurs hydratées
                $post = $updatePostForm->getData();

                // Valide la question/réponse et récupère les erreurs de formulaire si il y en a
                $validation = $blogManager->validatePost($post);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement du nouvel article
                $blogManager->updatePost($post, $existingFile);
                // renvoie la ligne de tableau pour l'affichage en JS
                return $this->render('default/dashboard/blogManagement/postsManagement/reloadPostImg.html.twig', array(
                    'post' => $post,
                ));
            }
            // renvoie le formulaire d'ajout pour l'affichage en JS
            return $this->render('default/dashboard/blogManagement/postsManagement/editPost.html.twig', array(
                'updatePostForm' => $updatePostForm->createView(),
                'post' => $post
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }


    /**
     * @param $slug
     * @param BlogManager $blogManager
     * @param Request $request
     * @return Response
     *
     * @Route("/dashboard/article/{slug}/suppression", name="post_delete")
     * @Method("GET")
     */
    public function deletePostAction($slug, BlogManager $blogManager, Request $request) {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // Supression de l'article
            $blogManager->deletePost($slug);
            // envoie le message de confirmation pour l'afficher en JS
            return new Response("Catégorie supprimée");
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /* PAGINATEUR */

    /**
     * @param Request $request
     * @param BlogManager $BlogManager
     * @return Response
     *
     * @Route("/articles", name="pagination_post")
     * @Method("GET")
     */
    public function paginationPostAction(Request $request, BlogManager $BlogManager)
    {
        if($request->isXmlHttpRequest()) {
            $paginationPosts = $BlogManager->getPaginatedPostList();
            return $this->render('default/blog/Pagination/paginatedIndex.html.twig', array(
                'paginationPosts' => $paginationPosts
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @param BlogManager $BlogManager
     * @param $category
     * @return Response
     *
     * @Route("/articles-par-categorie/{category}", name="pagination_postsCategory")
     * @Method("GET")
     */
    public function paginationPostsByCategoryAction(Request $request, BlogManager $BlogManager, $category)
    {
        if($request->isXmlHttpRequest()) {
            $category = $BlogManager->getCategory($category);
            $paginationPostsCategory = $BlogManager->getPaginatedPostsCategoryList($category);
            return $this->render('default/blog/Pagination/paginatedCategory.html.twig', array(
                'paginationPostsCategory' => $paginationPostsCategory,
                'category' => $category
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @param BlogManager $blogManager
     * @return Response
     *
     * @Route("/dashboard/articles", name="pagination_management_posts")
     * @Method("GET")
     */
    public function paginationPostManagementAction(Request $request, BlogManager $blogManager)
    {
        if($request->isXmlHttpRequest()) {
            $postsList = $blogManager->getPaginatedPostList();
            return $this->render('default/dashboard/blogManagement/postsManagement/paginatedTable.html.twig', array(
                'postsList' => $postsList
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }
}
