<?php


namespace AppBundle\Services\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class BanListener
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    public function generateExceptionBan(AuthenticationEvent $event)
    {
        $userLogged = $event->getAuthenticationToken()->getUsername();
        if ($userLogged !== 'anon.') {
            $user = $this->em->getRepository('AppBundle:User')->findOneBy(array("username" => $userLogged));
            if ($user->getEnabled() === false) {
                throw new \Exception('Compte desactivé');
            }
        }
    }
}
