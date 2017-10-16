<?php


namespace AppBundle\Controller;


use AppBundle\Form\Security\ChangeRoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
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

        // dernier nom d'utilisateur saisie par l'utilisateur
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('default/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("changer-role/{id}", name="changeRole")
     */
    public function changeRoleAction(Request $request, $id)
    {
        // Création de l'entity manager
        $em = $this->getDoctrine()->getManager();
        // Récupération du user par rapport à l'id passé en argument
        $user = $em->getRepository('AppBundle:User')->find($id);
        // Création du formulaire de changement de Role
        $form = $this->get('form.factory')->create(ChangeRoleType::class, $user);
        // Hydratation du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement de la modification
            $em->persist($user);
            $em->flush();
            // Redirection vers le dashboard après la modification
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('default/security/changeRole.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
