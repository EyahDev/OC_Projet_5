<?php


namespace AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/connexion", name="login")
     */
    public function loginAction(AuthenticationUtils $authUtils, Request $request)
    {
        // récupération des erreurs si il y en a
        $error = $authUtils->getLastAuthenticationError();
        if($error != null){
            $response = '<div class="alert alert-danger justify-content-center">Nom d\'utilisateur ou mot de passe invalide</div>';
            return new Response($response, 500);
        }

        // dernier nom d'utilisateur saisie par l'utilisateur
        $lastUsername = $authUtils->getLastUsername();
        $user = $this->get('doctrine')->getManager()->getRepository('AppBundle:User')->findOneBy(array('username' => $lastUsername));
        return $this->render('default/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

}
