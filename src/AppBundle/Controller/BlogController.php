<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use AppBundle\Services\CommentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * @Route("/blog/{slugCat}/{slugPost}", name="view-post")
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
     * @Route("blog/{category}", name="view-posts-by-category")
     */
    public function viewPostsByCategoryAction($category, BlogManager $blogManager) {
        // Récupération du nom de la catégorie à afficher
        $cat = $blogManager->getCategory($category);

        // Récupération de la liste de toutes les catégories
        $categories = $blogManager->getCategories();

        /* Gestion de la pagination */
        $paginationPostsCategory = $blogManager->getPaginatedPostsCategoryList($category);

        // Récupération des 3 derniers articles rédigés
        $threeLastPost = $blogManager->getThreeLastPosts();

        return $this->render("default/blog/categoryBlog.html.twig", array(
            'category' => $cat,
            'categories' => $categories,
            'paginationPostsCategory' => $paginationPostsCategory,
            'threeLastPost' => $threeLastPost
        ));
    }

    /* Gestion des catégories */

    /**
     * @Route("/dashboard/categorie/{slug}/edition/", name="edit_category")
     */
    public function editCategoryAction($slug, BlogManager $blogManager, Request $request) {

        // Récupération du formulaire de modification de la catégorie
        $updateCategoryForm = $blogManager->getFormUpdateCategory($slug);

        // Récupération du fichier existant
        $existingFile = $updateCategoryForm->getData()->getPhotoPath();

        // Hydration de l'entitée avec les valeurs du formulaire
        $updateCategoryForm->handleRequest($request);

        // Soumission du formulaire
        if ($updateCategoryForm->isSubmitted() && $updateCategoryForm->isValid()) {

            // Récupération de l'entitée Catégory avec les valeurs hydratées
            $category = $updateCategoryForm->getData();

            // Enregistrement de la nouvelle catégorie
            $blogManager->setUpdateCategory($category, $existingFile);

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
     * @Route("/dashboard/article/{slug}/edition/", name="edit_post")
     */
    public function editPostAction($slug, BlogManager $blogManager, Request $request) {

        // Récupération du formulaire de modification de l'article
        $updatePostForm = $blogManager->getUpdatePostForm($slug);

        // Récupération du fichier d'origine
        $existingFile = $updatePostForm->getData()->getImagePath();

        // Hydration de l'entitée avec les valeurs du formulaire
        $updatePostForm->handleRequest($request);

        // Soumission du formulaire
        if ($updatePostForm->isSubmitted() && $updatePostForm->isValid()) {

            // Récupération de l'entitée Post avec les valeurs hydratées
            $post = $updatePostForm->getData();

            // Enregistrement du nouvel article
            $blogManager->updatePost($post, $existingFile);

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

    /* Commentaires */

    /**
     * @Route("/dashboard/signalement/{id}/detail", name="view-detail-flagged")
     */
    public function viewDetailsFlagged(CommentManager $commentManager, $id) {
        // Récupération du commentaire ciblé
        $commentflagged = $commentManager->getCommentFlagged($id);

        return $this->render('default/dashboard/blogManagement/viewDetailFlagged.html.twig', array(
            'commentFlagged' => $commentflagged
        ));
    }

    /**
     * @Route("/dashboard/signalement/{id}/confirmation-suppression", name="advice_delete_comment")
     */
    public function deleteConfirmationCommentAction($id, CommentManager $commentManager) {
        // Récupération des informations lié au post
        $comment = $commentManager->getCommentFlagged($id);

        return $this->render(":default/dashboard/blogManagement:deleteConfirmationFlagged.html.twig", array(
            'infoComment' => $comment
        ));
    }

    /**
     * @Route("/dashboard/signalement/{id}/approbation", name="comment_approuved")
     */
    public function approuvedCommentAction($id, CommentManager $commentManager) {
        // Supression de l'article
        $commentManager->approuvedComment($id);

        // Rédirection vers le dashboard
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/dashboard/signalement/{id}/suppression", name="comment_delete")
     */
    public function deleteCommentAction($id, CommentManager $commentManager) {
        // Supression de l'article
        $commentManager->deleteComment($id);

        // Rédirection vers le dashboard
        return $this->redirectToRoute('dashboard');
    }

    /**
     * Ajoute un commentaire dynamiquement (AJAX)
     *
     * @param BlogManager $blogManager
     * @param Request $request
     * @param $slugPost
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/create-comm/{slugPost}", name="create-comm")
     */
    public function createCommentAction( BlogManager $blogManager, Request $request, $slugPost) {
        if ($request->isXmlHttpRequest()) {
            // Récupération de l'article via son slug
            $post = $blogManager->getPost($slugPost);

            // Récupération du formulaire de rédaction d'un nouveau commentaire
            $commentForm = $blogManager->getCommentForm();

            // Hydration de l'entitée avec les valeurs du formulaire
            $commentForm->handleRequest($request);

            // Soumission du formulaire
            if ($commentForm->isSubmitted() ) {
                // Récupération de l'auteur du message
                $user = $this->getUser();
                // Récupération de l'entitée Catégory avec les valeurs hydratées
                $comment = $commentForm->getData();
                // Récupère le résultat de la validation
                $validation = $blogManager->validateComment($comment);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistrement de la nouvelle catégorie
                $blogManager->setComment($comment, $post, $user);

                // Envoi du commentaire seul pour l'affichage en js
                return new Response('Commentaire ajouté');
            }
            // Envoi de le formulaire de commentaire pour l'affichage en js
            return $this->render("default/blog/comments/createComments.html.twig", array(
                'post' => $post,
                'commentForm' => $commentForm->createView()
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * Ajoute une réponse dynamiquement (AJAX)
     *
     * @param BlogManager $blogManager
     * @param Request $request
     * @param $slugPost
     * @param $parentId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/reply-com/{slugPost}/{parentId}", name="reply-com")
     */
    public function createReplyAction( BlogManager $blogManager, Request $request, $slugPost, $parentId) {
        // Verification de la provenance de la requete, est-ce de l'ajax?
        if ($request->isXmlHttpRequest()) {
            // Récupération de l'article via son slug
            $post = $blogManager->getPost($slugPost);
            // récupère le commentaire parent
            $commentParent = $blogManager->getComment($parentId);

            // Récupération du formulaire de rédaction d'un nouveau commentaire
            $replyForm = $blogManager->getReplyForm();

            // Hydration de l'entitée avec les valeurs du formulaire
            $replyForm->handleRequest($request);

            // Soumission du formulaire
            if ($replyForm->isSubmitted()) {
                // Récupération de l'auteur du message
                $user = $this->getUser();
                // Récupération de l'entitée Catégory avec les valeurs hydratées
                $comment = $replyForm->getData();
                // Récupère le résultat de la validation
                $validation = $blogManager->validateComment($comment);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Ajout du commentaire parent
                $comment->setParent($commentParent);
                // Enregistrement de la nouvelle catégorie
                $blogManager->setComment($comment, $post, $user);
                // Envoi de la réponse seule pour l'affichage en js
                return new Response('Réponse au commentaire ajoutée');
            }
            // Envoi de le formulaire de réponse  pour l'affichage en js
            return $this->render("default/blog/comments/replyComment.html.twig", array(
                'post' => $post,
                'replyForm' => $replyForm->createView()
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @param BlogManager $blogManager
     * @param $commentId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/flag-comm/{commentId}", name="flag-comm")
     */
    public function addflagAction(Request $request, BlogManager $blogManager, $commentId)
    {
        // Verification de la provenance de la requete, est-ce de l'ajax?
        if ($request->isXmlHttpRequest()) {
            // Ajout un signalement au commentaire et récupère le message pour le message flash JS
            $message = $blogManager->setCommentFlag($commentId);
            // Envoi le message flash pour l'affichage en JS
            return new Response($message);
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/post-management/pagination", name="pagination_post")
     */
    public function paginationPostAction(Request $request, BlogManager $BlogManager)
    {
        if($request->isXmlHttpRequest()) {
            $paginationPosts = $BlogManager->getPaginatedPostList();
            return $this->render(':default/blog:indexBlog.html.twig', array(
                'paginationPosts' => $paginationPosts
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/postsByCategory-management/pagination", name="pagination_postsCategory")
     */
    public function paginationCategoryAction(Request $request, BlogManager $BlogManager, $category)
    {
        if($request->isXmlHttpRequest()) {

            $paginationPostsCategory = $BlogManager->getPaginatedPostsCategoryList($category);
            return $this->render(':default/blog:categoryBlog.html.twig', array(
                'paginationPostsCategory' => $paginationPostsCategory
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("Blog/{slugPost}/comments", name="reload_comments_list")
     */
    public function reloadComments(Request $request, $slugPost, BlogManager $blogManager)
    {
        if ($request->isXmlHttpRequest()) {
            $post = $blogManager->getPost($slugPost);
            $commentsList = $blogManager->getCommentsByPost($post->getId());
            return $this->render('default/blog/comments/commentsList.html.twig', array(
                'post' => $post,
                'commentsList' => $commentsList
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }
}