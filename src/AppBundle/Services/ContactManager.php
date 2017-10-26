<?php

namespace AppBundle\Services;


use AppBundle\Form\Contact\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

class ContactManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $mailer;
    private $env;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em, RequestStack $request, SessionInterface $session, \Swift_Mailer $mailer, Environment $env) {

        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->env = $env;
    }

    /**
     * Récupération du formulaire de contact
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormCreateContact() {
        // Récupération du formulaire de contact
        $form = $this->formBuilder->create(ContactType::class);

        // Retourne le formulaire
        return $form;
    }

    public function sendMail($data) {
        // Préparation de l'email de contact
        $sendMail = (new \Swift_Message('Nouveau message depuis le formulaire de contact NAO'))
            ->setFrom(array('calcifer.hauru@gmail.com' => 'NAO - Nos amis les oiseaux'))
            ->setTo('calcifer.hauru@gmail.com')
            ->setBody($this->env->render(':default/email:mail.html.twig', array(
                'data' => $data
            )), 'text/html');

        // Envoi de l'email
        $this->mailer->send($sendMail);

    }
    
}