<?php

namespace AppBundle\Services;


use AppBundle\Form\Contact\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ContactManager
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

    public function getFormCreateContact() {
        // Récupération du formulaire de contact
        $form = $this->formBuilder->create(ContactType::class);

        // Retourne le formulaire
        return $form;
    }
    
}