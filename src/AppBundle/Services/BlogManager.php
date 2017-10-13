<?php

namespace AppBundle\Services;

use AppBundle\Entity\Post;
use AppBundle\Form\Blog\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class BlogManager
{
    private $formuBuilder;
    private $em;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em) {
        $this->formuBuilder = $formBuilder;
        $this->em = $em;
    }


    public function getPost($id = null) {
        if ($id == null) {
            $post = new Post();
        } else {
            $post = $this->em->getRepository('AppBundle:Post')->find($id);
        }

        return $post;
    }

    public function getFormCreatePost() {
        // Création d'un nouvel article
        $post = $this->getPost();

        // Récupération du formulaire de rédaction d'un nouvel article
        $form = $this->formuBuilder->create(PostType::class, $post);

        return $form;
    }
}