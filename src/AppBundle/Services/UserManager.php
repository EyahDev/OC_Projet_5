<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserManagement\ChangeRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserManager
 * @package AppBundle\Services
 */
class UserManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $session;
    private $validator;
    private $container;

    /**
     * UserManager constructor.
     * @param FormFactoryInterface $formBuilder
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     * @param SessionInterface $session
     * @param ValidatorInterface $validator
     */
    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em, RequestStack $request,
                                SessionInterface $session, ValidatorInterface $validator, ContainerInterface $container) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->session = $session;
        $this->validator = $validator;
        $this->container = $container;
    }

    /**
     * desactive le compte
     * @param User $user
     */
    public function disableAccount(User $user)
    {
        $user->setEnabled(false);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * récupère l'utilisateur grace à l'id
     * @param $id
     * @return User|null|object
     */
    public function getUser($id)
    {
        // Récupération du user par rapport à l'id passé en argument
        return $this->em->getRepository('AppBundle:User')->find($id);
    }

    /**
     * Active le compte
     * @param User $user
     */
    public function enableAccount(User $user)
    {
        $user->setEnabled(true);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * recupère le formulaire de changement de role
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getChangeRoleForm()
    {
        return $this->formBuilder->create(ChangeRoleType::class);
    }

    /**
     * Change le role de l'utilisateur et retourne le role écrit lisiblement
     * @param $roleName
     * @param User $user
     * @return string
     */
    public function setUserRole($roleName, User $user)
    {
        $role = $this->em->getRepository('AppBundle:Role')->findOneBy(array('name'=> $roleName));
        $user->setRoles($role);

        $roleShow = "";
        if ($roleName == 'ROLE_ADMIN') {
            $roleShow = 'Administrateur';
        } elseif ($roleName == 'ROLE_PROFESSIONAL') {
            $roleShow = "Professionnel";
        } elseif ($roleName == 'ROLE_USER') {
            $roleShow = "Particulier";
        }
        return $roleShow;
    }

    /**
     * Valide l'utilisateur
     * @param User $user
     * @return bool|string
     */
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

    /**
     * met a jour l'utilisateur
     * @param $user
     */
    public function updateUser($user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Retourne la liste paginée des utilisateurs
     * @param $id
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPaginatedUsersList($id)
    {
        // récupère la liste des questions/réponses
        $usersList = $this->em->getRepository('AppBundle:User')->findAllExceptUser($id);
        // récupère le service knp paginator
        $paginator  = $this->container->get('knp_paginator');
        // retourne les questions /réponse paginé selon la page passé en get
        return $paginator->paginate(
            $usersList/*$query*/, /* query NOT result */
            $this->request->getCurrentRequest()->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
    }
}
