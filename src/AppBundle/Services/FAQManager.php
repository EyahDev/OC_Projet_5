<?php

namespace AppBundle\Services;

use AppBundle\Entity\Faq;
use AppBundle\Form\Faq\EditFaqType;
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


    // Récupération du formulaire pour ajouter une nouvelle question/ réponse
    public function getFormNewFaq() {
        $faq = new Faq();

        return $this->formBuilder->create(NewFaqType::class, $faq);
    }

    public function setNewFaq($newFaq)
    {
        // Enregistrement de la nouvelle question/réponse
        $this->em->persist($newFaq);
        $this->em->flush();
    }

    public function removeFaq($faqId)
    {
        $faq = $this->em->getRepository('AppBundle:Faq')->find($faqId);
        $this->em->remove($faq);
        $this->em->flush();
        return 'Question supprimée';
    }

    public function getFormEditFaq($faqId)
    {
        $faq = $this->em->getRepository('AppBundle:Faq')->find($faqId);
        return $this->formBuilder->create(EditFaqType::class, $faq);
    }

    public function updateFaq($editedFaq)
    {
        $this->em->persist($editedFaq);
        $this->em->flush();
    }


}
