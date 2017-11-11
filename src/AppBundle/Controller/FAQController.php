<?php

namespace AppBundle\Controller;

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
                // récupère les données du formulaire dans un objet faq
                $newFaq = $newFaqForm->getData();
                // Valide la question/réponse et récupère les erreurs de formulaire si il y en a
                $validation = $faqManager->validateFaq($newFaq);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistre la question/réponse
                $faqManager->setFaq($newFaq);
                // renvoie la ligne de tableau pour l'affichage en JS
                return new Response('FAQ : ajout ok');
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
                // récupère les données du formulaire dans un objet faq
                $editedFaq = $editFaqForm->getData();
                // Valide la question/réponse et récupère les erreurs de formulaire si il y en a
                $validation = $faqManager->validateFaq($editedFaq);
                // si la validation n'est pas ok on renvoie les erreurs du validateur
                if($validation !== true) {
                    return new Response($validation,500);
                }
                // Enregistre la question/réponse
                $faqManager->setFaq($editedFaq);
                // renvoie la ligne de tableau pour l'affichage en JS
                return new Response('FAQ : édition ok');
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

    /**
     * @param Request $request
     * @return Response
     * @Route("/faq-management/pagination", name="pagination_faq")
     */
    public function paginationFaqAction(Request $request, FAQManager $FAQManager)
    {
        if($request->isXmlHttpRequest()) {
            $paginationFaq = $FAQManager->getPaginatedFaqList();
            return $this->render(':default/dashboard/websiteAdministration/faq:paginateTable.html.twig', array(
                'paginationFaq' => $paginationFaq
            ));
        }
        throw new AccessDeniedHttpException("Vous ne pouvez pas accéder à cette page");
    }
}
