<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommentManager
{
    private $em;
    private $request;
    private $container;

    /**
     * CommentManager constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $em, RequestStack $request, ContainerInterface $container)
    {
        $this->em = $em;
        $this->request = $request;
        $this->container = $container;
    }

    public function getCommentsFlagged() {
        // Récupération des commentaires signalés
        $commentsFlagged = $this->em->getRepository('AppBundle:Comment')->getCommentsFlagged();

        // Retourne les commentaires
        return $commentsFlagged;
    }

    public function getCommentFlagged($id) {
        // Récupération du commentaires signalé
        $commentFlagged = $this->em->getRepository('AppBundle:Comment')->find($id);

        // Retourne le commentaire
        return $commentFlagged;
    }

    public function approuvedComment($id) {
        // Récupération du commentaire signalé
        $commentFlagged = $this->em->getRepository('AppBundle:Comment')->find($id);

        // Approbation du commentaire
        $commentFlagged->setApprouved(true);

        // Enregistrement de la modification
        $this->em->persist($commentFlagged);
        $this->em->flush();
    }

    public function deleteComment($id) {
        // Récupération de la catégorie par son id
        $commentFlagged = $this->getCommentFlagged($id);

        // Supression de la catégorie
        $this->em->remove($commentFlagged);
        $this->em->flush();
    }

    public function getPaginatedCommentsFlaggedList()
    {
        // récupère la liste des questions/réponses
        $commentsFlagged = $this->getCommentsFlagged();
        // récupère le service knp paginator
        $paginator  = $this->container->get('knp_paginator');
        // retourne les questions /réponse paginé selon la page passé en get
        return $paginator->paginate(
            $commentsFlagged/*$query*/, /* query NOT result */
            $this->request->getCurrentRequest()->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
    }
}
