<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use AppBundle\Services\FAQManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FAQController extends Controller
{
    /**
     * Génere la vue front-office de la FAQ
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/faq", name="faq")
     */
    public function faqAction()
    {
        // récupère toutes les questions / réponses
        $faq = $this->getDoctrine()->getManager()->getRepository('AppBundle:Faq')->findAll();
        // génère la vue
        return $this->render('default/faq.html.twig', array(
            'faq' => $faq
        ));
    }

    /**
     * gestion de l'ajout de question/réponse
     *
     * @param Request $request
     * @param FAQManager $faqManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @route("/faq-management/new-faq", name="new-faq")
     */
    public function newFaqAction(Request $request, FAQManager $faqManager)
    {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // récupère le formulaire d'ajout d'une question / réponse
            $newFaqForm = $faqManager->getFormNewFaq();
            // hydrate le formulaire
            $newFaqForm->handleRequest($request);
            // teste si la requete est en POST et si les données sont valides
            if($newFaqForm->isSubmitted()) {
                if($newFaqForm->isValid()) {
                    // récupère les données du formulaire dans un objet faq
                    $newFaq = $newFaqForm->getData();
                    // Enregistre la question/réponse
                    $faqManager->setNewFaq($newFaq);
                    // renvoie la ligne de tableau pour l'affichage en JS
                    return $this->render('default/dashboard/websiteAdministration/faq/faqOnly.html.twig', array(
                        'faq' => $newFaq
                    ));
                }
                throw new \Exception("Le formulaire comporte des erreurs");
            }
            // renvoie le formulaire d'ajout pour l'affichage en JS
            return $this->render('default/dashboard/websiteAdministration/faq/newFaqForm.html.twig', array(
                'newFaqForm' => $newFaqForm->createView()
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * gestion de l'ajout de question/réponse
     *
     * @param Request $request
     * @param FAQManager $faqManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @route("/faq-management/edit/{faqId}", name="edit-faq")
     */
    public function editFaqAction(Request $request, FAQManager $faqManager, $faqId)
    {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // récupère le formulaire d'ajout d'une question / réponse
            $editFaqForm = $faqManager->getFormEditFaq($faqId);
            // hydrate le formulaire
            $editFaqForm->handleRequest($request);
            // teste si la requete est en POST et si les données sont valides
            if($editFaqForm->isSubmitted()) {
                if($editFaqForm->isValid()) {
                    // récupère les données du formulaire dans un objet faq
                    $editedFaq = $editFaqForm->getData();
                    // Enregistre la question/réponse
                    $faqManager->updateFaq($editedFaq);
                    // renvoie la ligne de tableau pour l'affichage en JS
                    return $this->render('default/dashboard/websiteAdministration/faq/faqOnly.html.twig', array(
                        'faq' => $editedFaq
                    ));
                }
                throw new \Exception("Le formulaire comporte des erreurs");
            }
            // renvoie le formulaire d'ajout pour l'affichage en JS
            return $this->render(':default/dashboard/websiteAdministration/faq:editFaqForm.html.twig', array(
                'editFaqForm' => $editFaqForm->createView(),
                'faqId' => $faqId
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }

    /**
     * Gestion de la suppression d'une question/réponse
     *
     * @param Request $request
     * @param FAQManager $faqManager
     * @param $faqId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/faq-management/remove/{faqId}", name="remove-faq")
     */
    public function removeFaqAction(Request $request, FAQManager $faqManager, $faqId)
    {
        // teste si la requete provient bien d'Ajax sinon on génère une exception
        if($request->isXmlHttpRequest()) {
            // supprime la question/réponse et récupère le message de confirmation
            $message = $faqManager->removeFaq($faqId);
            // envoie le message de confirmation pour l'afficher en JS
            return new Response($message);
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }
}
