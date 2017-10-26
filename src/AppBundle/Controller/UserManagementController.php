<?php


namespace AppBundle\Controller;




use AppBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class UserManagementController extends Controller
{
    /**
     * @param Request $request
     * @param UserManager $userManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("user-management/change-role/{id}", name="change_role")
     */
    public function changeRoleAction(Request $request, UserManager $userManager, $id)
    {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // récupère l'utilisateur
            $user = $userManager->getUser($id);
            // récupère le formulaire
            $changeRoleForm = $userManager->getChangeRoleForm();
            // hydrate le formulaire
            $changeRoleForm->handleRequest($request);
            // teste si le formulaire à été soumis
            if($changeRoleForm->isSubmitted()) {

                // récupère le role depuis les données du formulaire
                $role = $changeRoleForm->getData()['role'];

                $roleShow =$userManager->setUserRole($role, $user);

                // récupère le résultat de la validation
                $validation = $userManager->validateUser($user);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistre le role et retourne le role lisible
                $userManager->updateUser($user);
                // renvoie la role à afficher pour l'affichage en JS
                return new Response($roleShow);

            }
            // renvoie le formulaire d'ajout pour l'affichage en JS
            return $this->render('default/dashboard/websiteAdministration/userManagement/changeRole.html.twig', array(
                'changeRoleForm' => $changeRoleForm->createView(),
                'user' => $user
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("user-management/disable/{id}", name="disable_account")
     */
    public function disableAccountAction(Request $request,UserManager $userManager, $id)
    {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if ($request->isXmlHttpRequest()) {
            // récupère l'utilisateur
            $user = $userManager->getUser($id);
            // desactive le compte de l'utilsateur
            $userManager->disableAccount($user);

            return new Response("Le compte de l'utilisateur : ".$user->getUsername()." a été désactivé");
        }
        throw new AccessDeniedHttpException('Vous ne pouvez pas accéder à cette page.');
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("user-management/enable/{id}", name="enable_account")
     */
    public function enableAccountAction(Request $request, UserManager $userManager, $id)
    {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if ($request->isXmlHttpRequest()) {
            // récupère l'utilisateur
            $user = $userManager->getUser($id);
            // active le compte de l'utilsateur
            $userManager->enableAccount($user);

            return new Response("Le compte de l'utilisateur : ".$user->getUsername()." a été réactivé");
        }
        throw new AccessDeniedHttpException('Vous ne pouvez pas accéder à cette page.');
    }
}
