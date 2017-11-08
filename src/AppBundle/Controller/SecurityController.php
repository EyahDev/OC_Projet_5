<?php


namespace AppBundle\Controller;



use AppBundle\Entity\User;
use AppBundle\Services\SecurityManager;
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/mot-de-passe-oublie", name="lostPassword")
     */
    public function lostPasswordAction(Request $request, SecurityManager $securityManager)
    {
        // Teste si l'utilisateur n'est pas anonyme et redirige vers le dashboard
        if($this->getUser() != null) {
            return $this->redirectToRoute('dashboard');
        }
        // récupère le formulaire pour mot de passe oublié
        $form = $securityManager->getFormLostPassword();
        // Hydrate le formulaire
        $form->handleRequest($request);
        // Teste si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $securityManager->getUserWithEmailAdress($data['email']);
            // recherche l'utilisateur correspondant au mail et si le compte est actif si ko flashbag
             if( $user != false ) {
                 // cree un token et sa date d'expiration et l'ajoute a l'utilisateur
                 $securityManager->addTokenToUser($user);
                 // envoie le mail avec le lien de récupération
                 $securityManager->sendMail($user);
             }
            return $this->render("default/security/lostPassword.html.twig", array(
                'form' => $form->createView()
            ));
        }
        return $this->render("default/security/lostPassword.html.twig", array(
            'form' => $form->createView()
        ));
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/reinitialisation-mot-de-passe/{token}", name="resetPassword")
     */
    public function resetPasswordAction(Request $request, SecurityManager $securityManager, $token)
    {
        // récupere l'user correspondant au token
        $user = $securityManager->getUserWithToken($token);
        if($user != false) {
            // vérifie si le token est valide
            if ($securityManager->isTokenValide($user)) {
                // récupère le formulaire pour mot de passe oublié
                $form = $securityManager->getFormResetPassword();
                // Hydrate le formulaire
                $form->handleRequest($request);
                // Teste si le formulaire est soumis et valide
                if ($form->isSubmitted() && $form->isValid()) {
                    $data = $form->getData();
                    $securityManager->setNewPassword($user, $data['newPassword']);

                    return $this->render("default/security/resetPassword.html.twig", array(
                        'form' => $form->createView()
                    ));
                }

                return $this->render("default/security/resetPassword.html.twig", array(
                    'form' => $form->createView()
                ));
            }
        }
        return $this->render("default/security/expiredToken.html.twig");
    }

}
