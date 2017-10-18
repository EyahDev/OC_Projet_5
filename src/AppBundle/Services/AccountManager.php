<?php

namespace AppBundle\Services;

use AppBundle\Form\Signup\UpdateNameType;
use AppBundle\Form\Signup\UpdateFirstNameType;
use AppBundle\Form\Signup\AddLocationType;
use AppBundle\Form\Signup\UpdateNewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccountManager
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

    // Récupération de l'utilisateur courrant
    public function getUser($id) {
        // Récupération de la liste de toutes les catégories depuis le repository
        $user = $this->em->getRepository('AppBundle:User')->find($id);

        // Retourne l'utilisateur
        return $user;
    }

    // Informations personnelles
    public function getFormUpdateName($id) {
        // Récupération de l'utilisater par son id
        $user = $this->getUser($id);

        $form = $this->formBuilder->create(UpdateNameType::class, $user);

        return $form;
    }

    public function getFormUpdateFirstName($id) {
        // Récupération de l'utilisater par son id
        $user = $this->getUser($id);

        $form = $this->formBuilder->create(UpdateFirstNameType::class, $user);

        return $form;
    }

    public function getFormAddLocation($id) {
        // Récupération de l'utilisater par son id
        $user = $this->getUser($id);

        $form = $this->formBuilder->create(AddLocationType::class, $user);

        return $form;
    }

    public function getFormUpdateNewsletter($id) {
        // Récupération de l'utilisater par son id
        $user = $this->getUser($id);

        $form = $this->formBuilder->create(UpdateNewsletterType::class, $user);

        return $form;
    }

    // Mot de passe
}
