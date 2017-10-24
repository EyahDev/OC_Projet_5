<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Form\Account\UpdateNameType;
use AppBundle\Form\Account\UpdateFirstNameType;
use AppBundle\Form\Account\AddLocationType;
use AppBundle\Form\Account\UpdateNewsletterType;
use AppBundle\Form\Account\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $validator;
    private $encoder;

    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em,
                                RequestStack $request, SessionInterface $session,
                                ValidatorInterface $validator, UserPasswordEncoderInterface $encoder) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    /**
     * renvoie le formulaire de mise a jour du nom
     * @param $user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormUpdateName($user) {
        $form = $this->formBuilder->create(UpdateNameType::class, $user);

        return $form;
    }

    /**
     * renvoie le formulaire de mise à jour du prénom
     * @param $user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormUpdateFirstName($user) {
        $form = $this->formBuilder->create(UpdateFirstNameType::class, $user);

        return $form;
    }

    /**
     * Renvoie le formulaire de mise à jour de l'adresse
     * @param $user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormAddLocation($user) {
        $form = $this->formBuilder->create(AddLocationType::class, $user);

        return $form;
    }

    /**
     * renvoie le formulaire de mise à jour de l'inscription à la newsletter
     * @param $user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getFormUpdateNewsletter($user) {
        $form = $this->formBuilder->create(UpdateNewsletterType::class, $user);

        return $form;
    }

    /**
     * Effectue la mise à jour des données de l'utilisateur
     * @param $user
     */
    public function updateUser($user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    // Mot de passe

    /**
     * renvoie le formulaire de mot de passe
     */
    public function getFormUpdatePassword()
    {
        return $this->formBuilder->create(UpdatePasswordType::class);
    }

    /**
     * teste le mot de passe actuel
     */
    public function updatePassword(User $user,$plainPassword)
    {
        $encodedPassword = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $this->updateUser($user);
    }

    public function validateUser (User $user)
    {
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = "";
            foreach ($errors as $error) {
                $errorsString .=$error->getmessage().'<br>';
            }
            return $errorsString;
        }
        return true;
    }

    public function validatePassword($data, $user)
    {
        if(isset($data['password'])) {
            if ($this->encoder->isPasswordValid($user, $data["password"])) {
                return 'Mot de passe actuel invalide';
            }
        } else {
            return 'Le champs mot de passe actuel est vide';
        }
        if(isset($data['newPassword'])) {
            $plainPassword = $data['newPassword'];
        } else {
            return 'Les deux champs du nouveau mot de passe ne sont pas identiques';
        }
        // si le nouveau mot de passe fait moins de 6 caractères
        if(strlen($plainPassword) < 6) {
            return 'Le nouveau mot de passe doit contenir au moins 6 caractères';
        }
        // si le nouveau mot de passe est ne contient pas au moins une lettre et 1 chiffre
        if (!preg_match("/^(?=.*[a-zA-Z])(?=.*[0-9])/", $plainPassword)) {
            return 'Le nouveau mot de passe doit contenir au moins une lettre et un chiffre';
        }
        return true;
    }
}
