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

    /* Gestion des catégories */

    public function getCategories() {
        // Récupération de la liste de toutes les catégories depuis le repository
        $categories = $this->em->getRepository('AppBundle:Category')->findAll();

        // Retourne la liste des catégories
        return $categories;
    }

    public function getCategory($slug) {
        // Récupération de la catégorie par son id depuis le repository
        $category = $this->em->getRepository('AppBundle:Category')->findOneBy(array('slug' => $slug));

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

    public function getFormUpdateCategory($slug) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($slug);

        $form = $this->formBuilder->create(UpdateCategoryType::class, $category);

        return $form;
    }

    public function setCategory($category) {
        // Sauvegarde de la nouvelle catégorie
        $this->em->persist($category);

        // Enregistrement de la nouvelle catégorie
        $this->em->flush();
    }

    public function deleteCategory($slug) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($slug);

        // Vérification si il y a des articles dans cette catégorie
        if (count($category->getPosts()) != 0) {
            $this->session->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer une catégorie qui possède des articles.');

            return false;

        } else {
            // Supression de la catégorie
            $this->em->remove($category);
            $this->em->flush();
        }
    }


    /* Gestion des articles */

    public function getPost($slug) {
        // Récupération d'un article par son id
        $post = $this->em->getRepository('AppBundle:Post')->findOneBy(array('slug' => $slug));

        // Retourne l'article récupéré
        return $post;
    }

    public function getPostsByCategory($category) {
        // Récupération des articles par sa catégorie
        $posts = $this->getCategory($category)->getPosts();

        // Retourne les articles associés à la catégorie
        return $posts;
    }

    public function getPosts() {
        // Récupération de tous les articles existant
        $post = $this->em->getRepository('AppBundle:Post')->findAll();

        // Retourne l'article récupéré
        return $post;
    }

    public function getThreeLastPosts() {
        $threeLastPosts = $this->em->getRepository('AppBundle:Post')
            ->findBy(
                array('published' => '1'),
                array('publishedDate' => 'desc'),
                3);

        return $threeLastPosts;
    }

    public function getFormCreatePost() {
        // Création d'une nouvelle entitée Post
        $post = new Post();

        // Récupération du formulaire de rédaction d'un nouvel article
        $form = $this->formBuilder->create(CreatePostType::class, $post);

        // Retourne le formulaire
        return $form;
    }

    public function getUpdatePostForm($slug) {
        // Récupération de l'article à modifier
        $post = $this->getPost($slug);

        // Création du formulaire
        $form = $this->formBuilder->create(CreatePostType::class, $post);

        return $form;
    }

    public function setPost(Post $post, $user) {
        // Récupération de l'user en session
        $user = $this->session->get('UserTest');

        // Ajout de l'auteur dans le Post
        $post->setAuthor($user);
        $post->setPublished(1);
        $post->setPublishedDate(new \DateTime());

        // Sauvegarde du nouvel article
        $this->em->persist($post);

        // Enregistrement du nouvel article
        $this->em->flush();
    }

    public function updatePost(Post $post) {
        // Sauvegarde de la modification de l'article
        $this->em->persist($post);

        // Enregistrement de la modification de l'article
        $this->em->flush();
    }

    public function deletePost($slug) {
        // Récupération de la catégorie par son id
        $post = $this->getPost($slug);

        // Supression de la catégorie
        $this->em->remove($post);
        $this->em->flush();
    }
}
