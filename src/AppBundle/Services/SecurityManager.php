<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Form\Type\Security\LostPasswordType;
use AppBundle\Form\Type\Security\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class SecurityManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $mailer;
    private $env;
    private $encoder;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em,
                                RequestStack $request, Session $session, \Swift_Mailer $mailer,
                                Environment $env, UserPasswordEncoderInterface $encoder) {

        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->env = $env;
        $this->encoder = $encoder;
    }

    /**
     * Récupération du formulaire de réinitialisation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormLostPassword() {
        // Récupération du formulaire de contact
        $form = $this->formBuilder->create(LostPasswordType::class);

        // Retourne le formulaire
        return $form;
    }

    /**
     * @param User $user
     */
    public function sendMail(User $user) {
        // Préparation de l'email de contact
        $sendMail = (new \Swift_Message('Nouveau message depuis le formulaire de contact NAO'))
            ->setFrom(array('noreply@adriendesmet.com' => 'NAO - Nos amis les oiseaux'))
            ->setTo($user->getEmail())
            ->setBody($this->env->render('default/security/mailResetPassword.html.twig', array(
                'token' => $user->getResetToken()
            )), 'text/html');

        // Envoi de l'email
        $this->mailer->send($sendMail);
        // renvoie un flashbag confirmant l'envoi du mail
        $this->session->getFlashBag()->add('lostPasswordSuccess', 'Un mail vient de vous être envoyé');
    }

    /**
     * @param $email
     * @return User|bool|null|object
     */
    public function getUserWithEmailAdress($email)
    {
        // récupère l'utilisateur grace a son mail
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('email' => $email));
        // teste si l'utilisateur existe
        if ($user !== false) {
            // teste si le compte est actif
            if($user->getEnabled()) {
                // retourne l'utilisateur
                return $user;
            }
            // renvoie un flashbag d'erreur et retourne false
            $this->session->getFlashBag()->add('lostPasswordError', 'Le mot de passe ne peut pas être réinitialisé pour un compte desactivé');
            return false;
        }
        // renvoie un flashbag d'erreur et retourne false
        $this->session->getFlashBag()->add('lostPasswordError', "L'adresse email saisie ne correspond à aucun compte utilisateur");
        return false;
    }

    /**
     * @param User $user
     */
    public function addTokenToUser(User $user)
    {
        // crée le token
        $token = uniqid(uniqid());
        // crée la date d'expiration
        $expirationDate = new \DateTime('now +30 minute');
        // ajoute le token et sa date d'expiration a l'utilisateur
        $user->setResetToken($token)->setTokenExpirationDate($expirationDate);
        // enregistre l'utilisateur
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param $token
     * @return User|null|object
     */
    public function getUserWithToken($token)
    {
        return $this->em->getRepository('AppBundle:User')->findOneBy(array('resetToken' => $token));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isTokenValide(User $user)
    {
        // teste si la date d'expiration est passée
        if ($user->getTokenExpirationDate() > new \DateTime('now')) {
            return true;
        }
        return false;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormResetPassword()
    {
        return $this->formBuilder->create(ResetPasswordType::class);
    }

    /**
     * @param User $user
     * @param $newPassword
     */
    public function setNewPassword(User $user, $newPassword)
    {
        // encode le mot de passe
        $encodedPassword = $this->encoder->encodePassword($user, $newPassword);
        // met a jour le mot de passe
        $user->setPassword($encodedPassword);
        // enregistre l'utilisateur
        $this->em->persist($user);
        $this->em->flush();
        // renvoie un flashbag de confirmation de réinitialisation
        $this->session->getFlashBag()->add('resetPasswordSuccess', 'Le mot de passe a été modifié vous pouvez maintenant vous connecter');
    }
}
