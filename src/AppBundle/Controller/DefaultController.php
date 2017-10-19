<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Form\Signup\SignupType;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
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
    public function landingPageAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $role = $em->getRepository('AppBundle:Role')->findOneBy(array('name' => "ROLE_USER"));
        $userForm = $this->get('form.factory')->create(SignupType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a random salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            $password = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            // select default user role
            $user->setRoles($role);
            $user->setSignupDate(new \DateTime());
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render("default/landingPage.html.twig", array(
            'title' => 'Nouvel utilisateur',
            'form' => $userForm->createView()));
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
