<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
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
}
