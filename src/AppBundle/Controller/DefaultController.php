<?php

namespace AppBundle\Controller;

use AppBundle\Services\ContactManager;
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
    public function indexAction(Request $request, ContactManager $contactManager)
    {

        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
        $observations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->findAll();
        $species = $this->getDoctrine()->getManager()->getRepository('AppBundle:Species')->findAll();
        $differentSpeciesObservations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Observation')->getDifferentSpeciesObservations($species);

        /* Nous contacter */

        // Récupération du formulaire de contact
        $createContact = $contactManager->getFormCreateContact();
        // Hydration de l'entitée avec les valeurs du formulaire
        $createContact->handleRequest($request);
        // Soumission du formulaire
        if ($createContact->isSubmitted() && $createContact->isValid()) {
            // Si le formulaire est valide le mail est envoyé
            if($this->sendEmail($createContact->getData())){
                // Rédirection vers le dashboard
                return $this->redirectToRoute('dashboard');
            }else{
                var_dump("Une erreure s'est produite");
            }
        }
        // replace this example code with whatever you need
<<<<<<< HEAD
        return $this->render('default_integration/index.html.twig', array(
            'contactForm' => $createContact->createView(),
            'users' => $users,
            'observations' => $observations,
            'species' => $species,
            'differentSpeciesObservations' => $differentSpeciesObservations
        ));
=======
        return $this->render('layout/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
>>>>>>> 162fc212a7710fbbe1c10f634f7532c20687787c
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

    public function sendEmail($data){
        $ContactMail = 'oc_projet_5@laposte.net';
        $ContactPassword = '123456aA';

        $transport = \Swift_SmtpTransport::newInstance('smtp.laposte.net', 465,'ssl')
            ->setUsername($ContactMail)
            ->setPassword($ContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance($data["sujet"])
            ->setFrom($ContactMail)
            ->setTo(array(
                $ContactMail => $ContactMail
            ))
            ->setBody(($data["message"]."<br>ContactMail :".$data["email"]),'text/html');

        return $mailer->send($message);
    }
}
