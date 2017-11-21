<?php

namespace AppBundle\Controller;

use AppBundle\Services\AccountManager;
use AppBundle\Services\ContactManager;
use AppBundle\Services\MapsManager;
use AppBundle\Services\ObservationManager;
use AppBundle\Services\SpeciesManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @param ContactManager $contactManager
     * @param ObservationManager $observationManager
     * @param AccountManager $accountManager
     * @param SpeciesManager $speciesManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request, ContactManager $contactManager,
                                ObservationManager $observationManager, AccountManager $accountManager,
                                SpeciesManager $speciesManager)
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
     * @param Request $request
     * @param AccountManager $accountManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/landing-page", name="landingPage")
     * @Method({"GET", "POST"})
     */
    public function signUpLandingPageAAction(Request $request, AccountManager $accountManager)
    {
        // Récupération du formulaire d'inscription la landing page A
        $landingPageSignUpForm = $accountManager->getSignUpForm();

        // Hydration des valeurs
        $landingPageSignUpForm->handleRequest($request);

        // Soumission du formulaire
        if ($landingPageSignUpForm->isSubmitted() && $landingPageSignUpForm->isValid()) {

            // Récupération des valeurs du formulaire
            $user = $landingPageSignUpForm->getData();

            // Création du nouvel utilisateur
            $accountManager->setNewUser($user);

            // Rédirection vers la page de dashboard utilisateur
            return $this->redirectToRoute('dashboard');
        }

        return $this->render("default/landingPage.html.twig", array(
            'title' => 'Nouvel utilisateur',
            'form' => $landingPageSignUpForm->createView()));
    }

    /**
     * @param $id
     * @param MapsManager $maps
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     * @throws \Exception
     *
     * @Route("/ajout-confirmation-observation/{id}", name="ajoutPlusUn")
     * @Method("GET")
     */
    public function setSeeTooAction($id, MapsManager $maps, SessionInterface $session, Request $request) {
        if ($request->isXmlHttpRequest()) {
            // Ajout d'une confirmation d'observation à l'observation
            $maps->setSeeToo($id);

            $arraySeeToo = $session->get('seeToo');

            return new Response(json_encode($arraySeeToo));
        }

        throw new \Exception("Vous ne pouvez pas accéder à cette page.", 403);
    }


    /**
     * @param Request $request
     * @param MapsManager $maps
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/recherche-observations", name="rechercheObservations")
     * @Method({"GET", "POST"})
     */
    public function searchObservationsAction(Request $request, MapsManager $maps, SessionInterface $session)
    {
        if (is_array($session->get('seeToo'))) {
            $sessionIds = $session->get('seeToo');
        } else {
            $sessionIds = $session->set('seeToo', array());
        }

        $sessionIds = json_encode($sessionIds);

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

            // Passage de la variable de session avec le nombre de resultat de la recherche
            $session->set('nbResults', count($results));

            return $this->render(':default:searchObservations.html.twig', array(
                'results' => $results,
                'searchObservationForm' => $searchObservationForm->createView(),
                'seeToo' => $sessionIds
            ));
        }

        // Passage de la variable de session à false suite aucune recherche
        $session->set('nbResults', 0);

        return $this->render("default/searchObservations.html.twig",
            array(
                'searchObservationForm' => $searchObservationForm->createView(),
                'seeToo' => $sessionIds
            ));
    }

    /**
     * @param $slug
     * @param SpeciesManager $speciesManager
     * @param ObservationManager $observationManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/fiche-espece/{slug}", name="ficheEspece")
     * @Method({"GET", "POST"})
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
     *
     * @Route("/avancee-recherche", name="avanceeRecherche")
     * @Method("GET")
     */
    public function researchAction()
    {
        return $this->render("default/research.html.twig");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     *
     * @Route("/mentions-legales", name="mentionsLegales")
     * @Method("GET")
     */
    public function legalNoticesAction()
    {
        return $this->render("default/legalNotices.html.twig");
    }

    /**
     * @param ObservationManager $observationManager
     * @param AccountManager $accountManager
     * @param SpeciesManager $speciesManager
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/landing-page-A", name="landingPage1")
     * @Method("GET")
     */
    public function landingPageAAction(ObservationManager $observationManager, AccountManager $accountManager, SpeciesManager $speciesManager)
    {
        /* Statistiques */

        $users = $accountManager->getUsers();
        $observations = $observationManager->getObservationsValidated();
        $species = $speciesManager->getSpecies();
        $differentSpeciesObservations = $observationManager->getSpeciesObserved();

        return $this->render("default/landingPageA.html.twig", array(
            'users' => $users,
            'observations' => $observations,
            'species' => $species,
            'differentSpeciesObservations' => $differentSpeciesObservations
        ));

    }

    /**
     * @param Request $request
     * @param AccountManager $accountManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/landing-page-B", name="landingPage2")
     * @Method({"GET", "POST"})
     */
    public function landingPageBAction(Request $request, ObservationManager $observationManager, AccountManager $accountManager, SpeciesManager $speciesManager)
    {
        /* Statistiques */

        $users = $accountManager->getUsers();
        $observations = $observationManager->getObservationsValidated();
        $species = $speciesManager->getSpecies();
        $differentSpeciesObservations = $observationManager->getSpeciesObserved();

        // Récupération du formulaire d'inscription de la landing page B
        $landingPageBSignUpForm = $accountManager->getSignUpForm();

        // Hydration des valeurs
        $landingPageBSignUpForm->handleRequest($request);

        // Soumission du formulaire
        if ($landingPageBSignUpForm->isSubmitted() && $landingPageBSignUpForm->isValid()) {
            if ($accountManager->verfiyCaptcha() === true) {
                // Récupération des valeurs du formulaire
                $user = $landingPageBSignUpForm->getData();

                // Création du nouvel utilisateur
                $accountManager->setNewUser($user);

                // Rédirection vers la page d'accueil
                return $this->redirectToRoute('homepage');
            } else {
                $this->addFlash('notice', 'Si vous n\'êtes pas un robot, veuillez cocher la case.');

                // Rédirection vers la page d'accueil
                return $this->redirectToRoute('landingPageFinale');
            }
        }
        return $this->render("default/landingPageB.html.twig", array(
            'title' => 'Nouvel utilisateur',
            'form' => $landingPageBSignUpForm->createView(),
            'users' => $users,
            'observations' => $observations,
            'species' => $species,
            'differentSpeciesObservations' => $differentSpeciesObservations
        ));
    }

    /**
     * @param Request $request
     * @param AccountManager $accountManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/landing-page-A-finale", name="landingPageFinale")
     * @Method({"GET", "POST"})
     */
    public function landingPageAFinaleAction(Request $request, AccountManager $accountManager)
    {
        // Récupération du formulaire d'inscription de la landing page B
        $landingPageBSignUpForm = $accountManager->getSignUpForm();

            // Hydration des valeurs
            $landingPageBSignUpForm->handleRequest($request);

            // Soumission du formulaire
            if ($landingPageBSignUpForm->isSubmitted() && $landingPageBSignUpForm->isValid()) {
                if ($accountManager->verfiyCaptcha() === true) {
                    // Récupération des valeurs du formulaire
                    $user = $landingPageBSignUpForm->getData();

                    // Création du nouvel utilisateur
                    $accountManager->setNewUser($user);

                    // Rédirection vers la page d'accueil
                    return $this->redirectToRoute('homepage');
                } else {
                    $this->addFlash('notice', 'Si vous n\'êtes pas un robot, veuillez cocher la case.');

                    // Rédirection vers la page d'accueil
                    return $this->redirectToRoute('landingPageFinale');
                }
            }

        return $this->render("default/landingPageAFinale.html.twig", array(
            'title' => 'Nouvel utilisateur',
            'form' => $landingPageBSignUpForm->createView()));
    }
}

