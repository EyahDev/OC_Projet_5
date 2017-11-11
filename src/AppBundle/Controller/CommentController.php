<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use AppBundle\Services\CommentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CommentController extends Controller
{
    /**
     * @Route("/dashboard/signalement/{id}/approbation", name="comment_approuved")
     */
    public function approuvedCommentAction($id, CommentManager $commentManager, Request $request) {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // Supression de l'article
            $commentManager->approuvedComment($id);
            // envoie le message de confirmation pour l'afficher en JS
            return new Response("Commentaire approuvé");
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @Route("/dashboard/signalement/{id}/suppression", name="comment_delete")
     */
    public function deleteCommentAction($id, CommentManager $commentManager, Request $request) {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            /// Supression de l'article
            $commentManager->deleteComment($id);
            // envoie le message de confirmation pour l'afficher en JS
            return new Response("Commentaire supprimé");
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
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
     * @Route("dashboard/moderation-commentaires", name="pagination_comments_moderation")
     */
    public function paginationCommentsModerationAction(Request $request, CommentManager $commentManager)
    {
        if($request->isXmlHttpRequest()) {

            // Récupération de la liste des catégories
            $commentsFlagged = $commentManager->getPaginatedCommentsFlaggedList();
            return $this->render('default/dashboard/blogManagement/commentsModeration/paginatedTable.html.twig', array(
                'commentsFlagged' => $commentsFlagged,
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("Blog/{slugPost}/comments", name="reload_comments_list")
     */
    public function reloadCommentsAction(Request $request, $slugPost, BlogManager $blogManager)
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
