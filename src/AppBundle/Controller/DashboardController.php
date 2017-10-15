<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
        return $this->render("default/dashboard.html.twig", array(
            'users' => $users
        ));
    }
}