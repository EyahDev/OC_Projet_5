<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use AppBundle\Services\FAQManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FAQController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/faq", name="faq")
     */
    public function faqAction()
    {
        $faq = $this->getDoctrine()->getManager()->getRepository('AppBundle:Faq')->findAll();
        return $this->render('default/faq.html.twig', array(
            'faq' => $faq
        ));
    }

    /**
     * @param Request $request
     * @param FAQManager $faqManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @route("/faq-management/new-faq", name="new-faq")
     */
    public function newFaqAction(Request $request, FAQManager $faqManager)
    {
        if($request->isXmlHttpRequest()) {
            $newFaqForm = $faqManager->getFormNewFaq();

            return $this->render(':default/dashboard/websiteAdministration/faq:newFaqForm.html.twig', array(
                'newFaqForm' => $newFaqForm->createView()
            ));
        }
        throw new \Exception("Vous ne pouvez pas accéder à cette page");
    }
}
