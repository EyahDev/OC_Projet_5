<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use AppBundle\Services\ContactManager;
use AppBundle\Services\MapsManager;
use AppBundle\Services\ObservationManager;
use AppBundle\Services\SpeciesManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Form\Signup\SignupType;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, ContactManager $contactManager, ObservationManager $observationManager, AccountManager $accountManager, SpeciesManager $speciesManager)
    {
        /* Statistiques */

        $users = $accountManager->getUsers();
        $observations = $observationManager->getObservationsValidated();
        $species = $speciesManager->getSpecies();
        $differentSpeciesObservations = $observationManager->getSpeciesObserved();

        /* Nous contacter */

        // Récupération du formulaire de contact
        $createContact = $contactManager->getFormCreateContact();

        // Hydration de l'entitée avec les valeurs du formulaire
        $createContact->handleRequest($request);

        // Soumission du formulaire
        if ($createContact->isSubmitted() && $createContact->isValid()) {
            // Récupération des données du formulaire
            $data = $createContact->getData();

            // Préparation de l'email et envoi
            $contactManager->sendMail($data);

            return $this->redirectToRoute('homepage');
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'contactForm' => $createContact->createView(),
            'users' => $users,
            'observations' => $observations,
            'species' => $species,
            'differentSpeciesObservations' => $differentSpeciesObservations
        ));
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
            $user->setAvatarPath("img/default/avatar_default.png");
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
    public function searchObservationsAction(Request $request, MapsManager $maps, SessionInterface $session)
    {
        // Reset des markers
        if ($session->get('search') == false){
            $session->set('nbResults', 0);
            $maps->resetMarkersXML();
        }

        // Récupération des formulaires de recherche
        $searchObservationForm = $maps->searchObservationsForm();

        //Hydratation des valeurs
        $searchObservationForm->handleRequest($request);

        // Soumission des différents formulaires
        if ($searchObservationForm->isSubmitted() && $searchObservationForm->isValid()) {

            // Récupération du critère de recherche
            $criteria = $searchObservationForm->getData();

            // Récupération du résultat
            $results = $maps->searchObservations($criteria);

            // Vérification si il y a un résultat
            if (empty($results)) {
                $session->getFlashBag()->add('notice', 'Votre recherche ne donne aucun résultat, vous pouvez affiner avec la recherche avancée');
                return $this->redirectToRoute('rechercheObservations');
            }

            // Création des markers maps
            $maps->createMarkersXML($results);

            // Passage de la variable de session à true suite à la recherche
            $session->set('search', true);
            $session->set('nbResults', count($results));

            return $this->redirectToRoute('rechercheObservations');
        }

        // Passage de la variable de session à false suite aucune recherche
        $session->set('search', false);

        return $this->render("default/searchObservations.html.twig",
            array(
                'searchObservationForm' => $searchObservationForm->createView()
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/fiche-espece/{slug}", name="ficheEspece")
     */
    public function speciesAction($slug, SpeciesManager $speciesManager, ObservationManager $observationManager, Request $request)
    {
        // Récupération de toutes les informations lié à l'espèce
        $species = $speciesManager->getOneSpecies($slug);
        $photos = $speciesManager->getPhotosPath($slug);

        // Récupération du formulaire de modification de l'observation
        $speciesDescriptionForm = $speciesManager->getSpeciesDescriptionForm($slug);

        // Tableau de période
        $period = array();

        // Tableau des mois
        $months = array('Jan', 'Fev', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil', 'Aout', 'Sept', 'Oct', 'Nov', 'Dec');

        // Récupération des observations par mois de l'année
        $janv = $observationManager->getObservationsForJan($slug);
        array_push($period, $janv);
        $fev = $observationManager->getObservationsForFev($slug);
        array_push($period, $fev);
        $march = $observationManager->getObservationsForMarch($slug);
        array_push($period, $march);
        $april = $observationManager->getObservationsForApril($slug);
        array_push($period, $april);
        $may =$observationManager->getObservationsForMay($slug);
        array_push($period, $may);
        $june =$observationManager->getObservationsForJune($slug);
        array_push($period, $june);
        $july = $observationManager->getObservationsForJuly($slug);
        array_push($period, $july);
        $aug =$observationManager->getObservationsForAug($slug);
        array_push($period, $aug);
        $sept = $observationManager->getObservationsForSept($slug);
        array_push($period, $sept);
        $oct = $observationManager->getObservationsForOct($slug);
        array_push($period, $oct);
        $nov = $observationManager->getObservationsForNov($slug);
        array_push($period, $nov);
        $dec = $observationManager->getObservationsForDec($slug);
        array_push($period, $dec);

        $speciesDescriptionForm->handleRequest($request);

        if ($speciesDescriptionForm->isSubmitted() && $speciesDescriptionForm->isValid()) {
            $species = $speciesDescriptionForm->getData();

            $speciesManager->setSpeciesDescriptionForm($species);

            return $this->redirectToRoute('ficheEspece', array('slug' => $slug));
        }

        return $this->render("default/species.html.twig", array(
            'species' => $species,
            'period' => $period,
            'months' => $months,
            'photos' => $photos,
            'speciesDescriptionForm' => $speciesDescriptionForm->createView()
        ));
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/mentions-legales", name="mentionsLegales")
     */
    public function legalNoticesAction()
    {
        return $this->render("default/legalNotices.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/landing-page-A", name="landingPage1")
     */
    public function landingPageAAction(ObservationManager $observationManager, AccountManager $accountManager, SpeciesManager $speciesManager)
    {
        /* Statistiques */

        $users = $accountManager->getUsers();
        $observations = $observationManager->getObservationsValidated();
        $species = $speciesManager->getSpecies();
        $differentSpeciesObservations = $observationManager->getSpeciesObserved();
        
        return $this->render("default/landingPage1.html.twig", array(
            'users' => $users,
            'observations' => $observations,
            'species' => $species,
            'differentSpeciesObservations' => $differentSpeciesObservations
        ));
        
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/landing-page-B", name="landingPage2")
     */
    public function landinfPageBAction(Request $request, UserPasswordEncoderInterface $encoder)
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
            $user->setAvatarPath("img/default/avatar_default.png");
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render("default/landingPage2.html.twig", array(
            'title' => 'Nouvel utilisateur',
            'form' => $userForm->createView()));
    }
}
