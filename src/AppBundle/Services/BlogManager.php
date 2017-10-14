<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use AppBundle\Form\Blog\CreateCategoryType;
use AppBundle\Form\Blog\CreatePostType;
use AppBundle\Form\Blog\UpdateCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BlogManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em, RequestStack $request, SessionInterface $session) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
    }

    /* Gestion de l'auteur */

    // Récupération de l'auteur
    public function getUser($id) {
        // Récupération de la liste de toutes les catégories depuis le repository
        $user = $this->em->getRepository('AppBundle:User')->find($id);

        // Retourne l'auteur du post
        return $user;
    }


    /* Gestion des catégories */

    public function getCategories() {
        // Récupération de la liste de toutes les catégories depuis le repository
        $categories = $this->em->getRepository('AppBundle:Category')->findAll();

        // Retourne la liste des catégories
        return $categories;
    }

    public function getCategory($id) {
        // Récupération de la catégorie par son id depuis le repository
        $category = $this->em->getRepository('AppBundle:Category')->find($id);

        // Retourne la catégorie
        return $category;
    }

    public function getFormCreateCategory() {
        // Création d'une nouvelle entitée Category
        $category = new Category();

        // Récupération du formulaire de création d'une nouvelle catégorie
        $form = $this->formBuilder->create(CreateCategoryType::class, $category);

        // Retourne le formulaire
        return $form;
    }

    public function getFormUpdateCategory($id) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($id);

        $form = $this->formBuilder->create(UpdateCategoryType::class, $category);

        return $form;
    }

    public function setCategory($category) {
        // Sauvegarde de la nouvelle catégorie
        $this->em->persist($category);

        // Enregistrement de la nouvelle catégorie
        $this->em->flush();
    }

    public function deleteCategory($id) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($id);

        // Supression de la catégorie
        $this->em->remove($category);
        $this->em->flush();
    }


    /* Gestion des articles */

    public function getPost($id) {
        // Récupération d'un article par son id
        $post = $this->em->getRepository('AppBundle:Post')->find($id);

        // Retourne l'article récupéré
        return $post;
    }

    public function getPosts() {
        // Récupération de tous les articles existant
        $post = $this->em->getRepository('AppBundle:Post')->findAll();

        // Retourne l'article récupéré
        return $post;
    }

    public function getFormCreatePost() {
        // Création d'une nouvelle entitée Post
        $post = new Post();

        // Récupération du formulaire de rédaction d'un nouvel article
        $form = $this->formBuilder->create(CreatePostType::class, $post);

        // Retourne le formulaire
        return $form;
    }

    public function setPost($post) {
        // Récupération de l'user en session
        $user = $this->session->get('UserTest');

        // Sauvegarde de l'utilisateur
        $this->em->persist($user);

        // Ajout de l'auteur dans le Post
        $post->setAuthor($user);
        $post->setPublished(1);
        $post->setPublishedDate(new \DateTime());

        // Sauvegarde du nouvel article
        $this->em->persist($post);

        // Enregistrement du nouvel article
        $this->em->flush();
    }
}
