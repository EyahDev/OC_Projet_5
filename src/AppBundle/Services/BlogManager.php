<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\Blog\CreateCategoryQuicklyType;
use AppBundle\Form\Blog\CreateCategoryType;
use AppBundle\Form\Blog\CreatePostType;
use AppBundle\Form\Blog\UpdateCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BlogManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $container;
    private $fileSystem;

    public function __construct(FormFactoryInterface $formBuilder,
                                EntityManagerInterface $em,
                                RequestStack $request,
                                SessionInterface $session,
                                ContainerInterface $container,
                                Filesystem $filesystem)
    {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->container = $container;
        $this->fileSystem = $filesystem;
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

    public function getFormCreateQuicklyCategory() {
        // Création d'une nouvelle entitée Category
        $category = new Category();

        // Récupération du formulaire de création d'une nouvelle catégorie
        $form = $this->formBuilder->create(CreateCategoryQuicklyType::class, $category);

        // Retourne le formulaire
        return $form;
    }

    public function getFormUpdateCategory($slug) {
        // Récupération de la catégorie par son id
        $category = $this->getCategory($slug);

        $form = $this->formBuilder->create(UpdateCategoryType::class, $category);

        return $form;
    }

    public function setCategory(Category $category) {
        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('categories_directory');

        // Récupération du nouveau fichier
        $newFile = $category->getPhotoPath();

        if ($newFile == null) {
            // Ajout de l'image par défault
            $category->setPhotoPath('img/default/category_default.jpg');

        } else {
            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$newFile->guessExtension();

            // Déplacement du fichier dans le dossiers des catégories
            $newFile->move($path, $fileName);

            // Ajout de l'image dans la catégorie
            $filePath = "uploads/categories_files/".$fileName;

            // Ajout des image
            $category->setPhotoPath($filePath);
        }

        // Sauvegarde de la nouvelle catégorie
        $this->em->persist($category);

        // Enregistrement de la nouvelle catégorie
        $this->em->flush();
    }

    public function setUpdateCategory(Category $category, $existingFile) {
        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('categories_directory');

        // Récupération du nouveau fichier
        $newFile = $category->getPhotoPath();

        if ($newFile == null) {
            // Ajout de l'image par défault
            $category->setPhotoPath($existingFile);

        } else {
            if ($existingFile != 'img/default/category_default.jpg') {
                // Suppression de l'ancienne photo
                $this->fileSystem->remove(array($existingFile));
            }

            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$newFile->guessExtension();

            // Déplacement du fichier dans le dossiers des catégories
            $newFile->move($path, $fileName);

            // Ajout de l'image dans la catégorie
            $filePath = "uploads/categories_files/".$fileName;

            // Ajout des image
            $category->setPhotoPath($filePath);
        }

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
            // Création du message flash d'erreur
            $this->session->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer une catégorie qui possède des articles.');

        } else {
            // Vérification pour ne pas supprimer l'image pas défaut
            if ($category->getPhotoPath() != 'img/default/category_default.jpg') {
                // Suppression de l'ancienne photo
                $this->fileSystem->remove(array($category->getPhotoPath()));
            }

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
        // Ajout de l'auteur dans le Post
        $post->setAuthor($user);

        // Publication automatique de l'article
        $post->setPublished(1);

        // Ajout de la date de publication
        $post->setPublishedDate(new \DateTime());

        // Récupération de l'image sélectionnée si disponible
        $newFile = $post->getImagePath();

        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('posts_directory');

        if ($newFile == null) {
            // Ajout de l'image par défault
            $post->setImagePath('img/default/post_default.jpg');

        } else {
            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$newFile->guessExtension();

            // Déplacement du fichier dans le dossiers des catégories
            $newFile->move($path, $fileName);

            // Ecriture de du nouveau chemin
            $filePath = "uploads/posts_files/".$fileName;

            // Association de l'image à l'article
            $post->setImagePath($filePath);
        }

        // Sauvegarde du nouvel article
        $this->em->persist($post);

        // Enregistrement du nouvel article
        $this->em->flush();
    }

    public function updatePost(Post $post, $existingFile) {
        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('posts_directory');

        // Récupération du nouveau fichier
        $newFile = $post->getImagePath();

        if ($newFile == null) {
            // Ajout de l'image par défault
            $post->setImagePath($existingFile);

        } else {
            if ($existingFile != 'img/default/post_default.jpg') {
                // Suppression de l'ancienne photo
                $this->fileSystem->remove(array($existingFile));
            }

            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$newFile->guessExtension();

            // Déplacement du fichier dans le dossiers des catégories
            $newFile->move($path, $fileName);

            // Ajout de l'image dans la catégorie
            $filePath = "uploads/posts_files/".$fileName;

            // Ajout des image
            $post->setImagePath($filePath);
        }

        // Sauvegarde de la modification de l'article
        $this->em->persist($post);

        // Enregistrement de la modification de l'article
        $this->em->flush();
    }

    public function deletePost($slug) {
        // Récupération de la catégorie par son id
        $post = $this->getPost($slug);

        // Vérification pour ne pas supprimer l'image pas défaut
        if ($post->getImagePath() != 'img/default/post_default.jpg') {
            // Suppression de l'ancienne photo
            $this->fileSystem->remove(array($post->getImagePath()));
        }

        // Supression de l'article
        $this->em->remove($post);
        $this->em->flush();
    }

    /* Gestion des commentaires */

    public function getCommentForm() {
        // Création d'un nouveau commentaire
        $comment = new Comment();

        // Récupération du formulaire de création d'un commentaire
        $form = $this->formBuilder->create('AppBundle\Form\Blog\NewCommentType', $comment);

        // Retourne le formulaire
        return $form;
    }

    public function setComment(Comment $comment, Post $post, $user) {
        // Récupération de l'utilisateur courant
        $comment->setAuthor($user);

        // Création de la date du jour pour
        $comment->setDate(new \DateTime());

        // Passage de l'approbation false
        $comment->setApprouved(0);

        // Ajout du commentaire dans le Post
        $post->addComment($comment);

        // Enregistrement de la catégorie
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * Renvoie le commentaire demandé avec une recherche par id
     * @param $id
     * @return null|object
     */
    public function getComment($id)
    {
        // Récupération du commentaire grace à son id
        return $this->em->getRepository("AppBundle:Comment")->find($id);
    }

    /**
     * Renvoie le formulaire créé pour répondre à un commentaire
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getReplyForm() {
        // Création d'un nouveau commentaire
        $comment = new Comment();

        // Récupération du formulaire de réponse à un commentaire
        $form = $this->formBuilder->create('AppBundle\Form\Blog\ReplyCommentType', $comment);

        // Retourne le formulaire
        return $form;
    }

    public function setCommentFlag($commentId)
    {
        if($this->session->has('flaggedComments')) {
            $flaggedComments = $this->session->get('flaggedComments');
            if(in_array($commentId, $flaggedComments)) {
                return "Vous avez déjà signalé ce commentaire.";
            }
            array_push($flaggedComments, $commentId);

        } else {
            $flaggedComments[] = $commentId;
        }
        $this->session->set('flaggedComments', $flaggedComments);
        // récupère le commentaire
        $comment = $this->getComment($commentId);
        // récupère ét incremente le compteur de signalement
        $flaggedNb = $comment->getFlagged()+1;
        // teste si le commentaire a été approuvé
        if ($comment->getApprouved()) {
            // message indiquant que le commentaire a déjà été approuvé
            $message = "Ce commentaire a déjà été approuvé par le modérateur. Aucun signalement supplémentaire ne sera transmis.";
        } else {
            // enregistre la modification du commentaire
            $comment->setFlagged($flaggedNb);
            $this->em->persist($comment);
            $this->em->flush();
            // message de prise en compte du signalement
            $message = "Votre signalement a bien été pris en compte. La modération sera faite dans les plus brefs délais.";
        }
        return $message;
    }
}
