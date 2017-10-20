<?php

namespace AppBundle\Services;

use AppBundle\Entity\Faq;
use AppBundle\Form\Faq\NewFaqType;
use AppBundle\Form\Signup\UpdateNameType;
use AppBundle\Form\Signup\UpdateFirstNameType;
use AppBundle\Form\Signup\AddLocationType;
use AppBundle\Form\Signup\UpdateNewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FAQManager
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
    public function getFaq($id) {
        // Récupération de la liste de toutes les catégories depuis le repository
        return $this->em->getRepository('AppBundle:Faq')->find($id);
    }

    // Informations personnelles
    public function getFormNewFaq() {
        $faq = new Faq();

        return $this->formBuilder->create(NewFaqType::class, $faq);
    }


}
