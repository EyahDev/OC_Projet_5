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
            dump($data);

            // Préparation de l'email et envoi
            $contactManager->sendMail($data);
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
            $maps->resetMarkersXML();
        }

        // Récupération des formulaires de recherche
        $searchByReferenceNameForm = $maps->searchObservationsByReferenceNameForm();
        $searchVernacularForm = $maps->searchObservationsByVernacularForm();
        $searchByType = $maps->searchObservationsByTypeForm();
        $searchByFamily = $maps->searchObservationsByFamilyForm();

        // Hydration avec les valeurs du formulaire
        $searchByReferenceNameForm->handleRequest($request);
        $searchVernacularForm->handleRequest($request);
        $searchByType->handleRequest($request);
        $searchByFamily->handleRequest($request);

        // Soumission des différents formulaires
        if ($searchByReferenceNameForm->isSubmitted() && $searchByReferenceNameForm->isValid()) {

            // Récupération du critère de recherche
            $criteria = $searchByReferenceNameForm->getData();

            // Récupération du résultat
            $result = $maps->searchObservationsBySpecies($criteria);

            // Vérification si il y a un résultat
            if (empty($result)) {
                $session->getFlashBag()->add('notice', 'Votre recherche ne donne aucun résultat');
                return $this->redirectToRoute('rechercheObservations');
            }

            // Création des markers maps
            $maps->createMarkersXML($result);

            // Passage de la variable de session à true suite à la recherche
            $session->set('search', true);

            return $this->redirectToRoute('rechercheObservations');
        }

        if ($searchVernacularForm->isSubmitted() && $searchVernacularForm->isValid()) {
            // Récupération du critère de recherche
            $criteria = $searchVernacularForm->getData();

            // Récupération du résultat
            $result = $maps->searchObservationsByVernacular($criteria);

            // Vérification si il y a un résultat
            if (empty($result)) {
                $session->getFlashBag()->add('notice', 'Votre recherche ne donne aucun résultat');
            }

            // Création des markers maps
            $maps->createMarkersXML($result);

            // Passage de la variable de session à true suite à la recherche
            $session->set('search', true);

            return $this->redirectToRoute('rechercheObservations');
        }

        if ($searchByType->isSubmitted() && $searchByType->isValid()) {
            // Récupération du critère de recherche
            $criteria = $searchByType->getData();

            // Récupération du résultat
            $result = $maps->searchObservationsByType($criteria);

            dump($result);

            // Vérification si il y a un résultat
            if (empty($result)) {
                $session->getFlashBag()->add('notice', 'Votre recherche ne donne aucun résultat');
                return $this->redirectToRoute('rechercheObservations');
            }

            // Création des markers maps
            $maps->createMarkersXML($result);

            // Passage de la variable de session à true suite à la recherche
            $session->set('search', true);

            return $this->redirectToRoute('rechercheObservations');
        }

        if ($searchByFamily->isSubmitted() && $searchByFamily->isValid()) {

            $criteria = $searchByFamily->getData();

            $result = $maps->searchObservationsByFamily($criteria);

            // Vérification si il y a un résultat
            if (empty($result)) {
                $session->getFlashBag()->add('notice', 'Votre recherche ne donne aucun résultat');
                return $this->redirectToRoute('rechercheObservations');
            }

            $maps->createMarkersXML($result);

            // Passage de la variable de session à true suite à la recherche
            $session->set('search', true);

            return $this->redirectToRoute('rechercheObservations');
        }

        // Passage de la variable de session à false suite aucune recherche
        $session->set('search', false);

        return $this->render("default/searchObservations.html.twig",
            array(
                'searchReferenceNameForm' => $searchByReferenceNameForm->createView(),
                'searchVernacularNameForm' => $searchVernacularForm->createView(),
                'searchTypeForm' => $searchByType->createView(),
                'searchFamilyForm' => $searchByFamily->createView(),
            ));
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/mentions-legales", name="mentionsLegales")
     */
    public function leagalNoticesAction()
    {
        return $this->render("default/legalNotices.html.twig");
    }

}
