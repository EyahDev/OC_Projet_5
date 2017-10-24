<?php

namespace AppBundle\Services;

use AppBundle\Form\Account\UpdateNameType;
use AppBundle\Form\Account\UpdateFirstNameType;
use AppBundle\Form\Account\AddLocationType;
use AppBundle\Form\Account\UpdateNewsletterType;
use AppBundle\Form\Account\UpdatePasswordType;
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

    /**
     * renvoie un utilisateur a partir de son id
     * @param $id
     * @return \AppBundle\Entity\User|null|object
     */
    public function getUser($id) {
        // Récupération de la liste de toutes les catégories depuis le repository
        $user = $this->em->getRepository('AppBundle:User')->find($id);

        // Retourne l'utilisateur
        return $user;
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
    public function updatePassword($user,$encodedPassword)
    {
        $user->setPassword($encodedPassword);
        $this->updateUser($user);
    }
}
