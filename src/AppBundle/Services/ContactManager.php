<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Form\Type\Contact\ContactType;
use AppBundle\Form\Type\Contact\ContactUsType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class ContactManager
{
    private $formBuilder;
    private $mailer;
    private $env;

    /**
     * ContactManager constructor.
     * @param FormFactoryInterface $formBuilder
     * @param \Swift_Mailer $mailer
     * @param Environment $env
     */
    public function __construct(FormFactoryInterface $formBuilder, \Swift_Mailer $mailer, Environment $env) {

        $this->formBuilder = $formBuilder;
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

    /**
     * Récupération du formulaire de contact
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormCreateContactUs() {
        // Récupération du formulaire de contact
        $form = $this->formBuilder->create(ContactUsType::class);

        // Retourne le formulaire
        return $form;
    }

    public function sendMail($data) {
        // Préparation de l'email de contact
        $sendMail = (new \Swift_Message('Nouveau message depuis le formulaire de contact NAO'))
            ->setFrom(array('noreply@adriendesmet.com' => 'NAO - Nos amis les oiseaux'))
            ->setTo('calcifer.hauru@gmail.com')
            ->setBody($this->env->render(':default/email:mail.html.twig', array(
                'data' => $data
            )), 'text/html');

        // Envoi de l'email
        $this->mailer->send($sendMail);

    }

    public function sendMailUser($data) {
        // Préparation de l'email de contact
        $sendMailUser = (new \Swift_Message('Nouveau message depuis le formulaire de contact NAO'))
            ->setFrom(array('noreply@adriendesmet.com' => 'NAO - Nos amis les oiseaux'))
            ->setTo('calcifer.hauru@gmail.com')
            ->setBody($this->env->render(':default/email:mailUser.html.twig', array(
                'data' => $data
            )), 'text/html');

        // Envoi de l'email
        $this->mailer->send($sendMailUser);

    }

    /**
     * récupère et retourne les erreurs des validations du formulaires
     * @param FormInterface $form
     * @return string
     */
    private function getErrorMessages(FormInterface $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }
        // récupère les erreurs des formulaires enfant si il y en a
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }
        $errorsString = implode('<br>',$errors);
        return $errorsString;
    }

    /**
     * vérifie si le formulaire valide si oui retourne true si non retourne les messages d'erreurs
     * @param FormInterface $form
     * @return bool|string
     */
    public function validateForm(FormInterface $form)
    {
        $errorMessages = $this->getErrorMessages($form);
        if ($errorMessages == '') {
            return true;
        }
        return $errorMessages;
    }

    public function sendSignUpMail(User $user)
    {
        // Préparation de l'email de contact
        $sendMailUser = (new \Swift_Message('Inscription au site NAO'))
            ->setFrom(array('noreply@adriendesmet.com' => 'NAO - Nos amis les oiseaux'))
            ->setTo($user->getEmail())
            ->setBody($this->env->render('default/email/signUpMail.html.twig', array(
                'user' => $user
            )), 'text/html');

        // Envoi de l'email
        $this->mailer->send($sendMailUser);
    }
}
