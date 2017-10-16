<?php

namespace AppBundle\Controller;

use AppBundle\Services\BlogManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/landing-page", name="landingPage")
     */
    public function landingPageAction()
    {
        return $this->render("default/landingPage.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/saisie-observation", name="saisieObservation")
     */
    public function addObservationAction()
    {
        return $this->render("default/addObservation.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recherche-observations", name="rechercheObservations")
     */
    public function searchObservationsAction()
    {
        return $this->render("default/searchObservations.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/fiche-espece/{species}", name="ficheEspece")
     */
    public function speciesAction($species)
    {
        return $this->render("default/species.html.twig", array('species' => $species));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blog/categorie/{category}", name="categoryBlog")
     */
    public function categoryBlogAction($category)
    {
        return $this->render("default/blog/categoryBlog.html.twig", array('category' => $category));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/avancee-recherche", name="avanceeRecherche")
     */
    public function researchAction()
    {
        return $this->render("default/research.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/faq", name="faq")
     */
    public function faqAction()
    {
        return $this->render("default/faq.html.twig");
    }
}
