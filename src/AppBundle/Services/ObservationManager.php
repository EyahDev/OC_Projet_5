<?php

namespace AppBundle\Services;

use AppBundle\Entity\Observation;
use AppBundle\Entity\User;
use AppBundle\Form\Type\Observations\CreateObservationType;
use AppBundle\Form\Type\Observations\ModifObservationByObserverType;
use AppBundle\Form\Type\Observations\ModifObservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ObservationManager
{
    private $formBuilder;
    private $em;
    private $request;
    private $container;
    private $filesystem;
    private $validator;

    /**
     * ObservationManager constructor.
     * @param FormFactoryInterface $formBuilder
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     * @param ContainerInterface $container
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     */
    public function __construct(FormFactoryInterface $formBuilder, EntityManagerInterface $em,
                                RequestStack $request, ContainerInterface $container, Filesystem $filesystem,
                                ValidatorInterface $validator) {
        $this->formBuilder = $formBuilder;
        $this->em = $em;
        $this->request = $request;
        $this->container = $container;
        $this->filesystem = $filesystem;
        $this->validator = $validator;
    }

    /* Récupération des observations par mois */

    /**
     * Pour Janvier
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForJan($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForJan($slug);

        return count($result);
    }

    /**
     * Pour Février
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForFev($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForFev($slug);

        return count($result);
    }

    /**
     * Pour Mars
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForMarch($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForMarch($slug);

        return count($result);
    }

    /**
     * Pour Avril
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForApril($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForApril($slug);

        return count($result);
    }

    /**
     * Pour Mai
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForMay($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForMay($slug);

        return count($result);
    }

    /**
     * Pour Juin
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForJune($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForJune($slug);

        return count($result);
    }

    /**
     * Pour Juillet
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForJuly($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForJuly($slug);

        return count($result);
    }

    /**
     * Pour Aout
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForAug($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForAug($slug);

        return count($result);
    }

    /**
     * Pour Septembre
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForSept($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForSept($slug);

        return count($result);
    }

    /**
     * Pour Octobre
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForOct($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForOct($slug);

        return count($result);
    }

    /**
     * Pour Novembre
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForNov($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForNov($slug);

        return count($result);
    }

    /**
     * Pour Décembre
     *
     * @param $slug
     * @return int
     */
    public function getObservationsForDec($slug) {
        $result = $this->em->getRepository('AppBundle:Observation')->getObservationForDec($slug);

        return count($result);
    }


    /* Recherche d'une observation */


    /**
     * Recherche par nom scientifique
     *
     * @param $criteria
     * @return array
     */
    public function getObservationsByReferenceName($criteria) {
        // Récupération des observations pas nom de scientifique
        return $this->em->getRepository('AppBundle:Observation')->getObservationBySpecies($criteria);
    }

    /**
     * Recherche par nom commun
     *
     * @param $criteria
     * @return array
     */
    public function getObservationsByVernacular($criteria) {
        // Récupération des observations pas nom commun
        return $this->em->getRepository('AppBundle:Observation')->getObservationByVernacularName($criteria);
    }

    /**
     * Recherche par ordre
     *
     * @param $criteria
     * @return array
     */
    public function getObservationsByType($criteria) {
        // Récupération des observations par ordre
        return $this->em->getRepository('AppBundle:Observation')->getObservationByType($criteria);
    }

    /**
     * Recherche par famille
     *
     * @param $criteria
     * @return array
     */
    public function getObservationsByFamily($criteria) {
        // Récupération des observations par famille
        return $this->em->getRepository('AppBundle:Observation')->getObservationByFamily($criteria);
    }

    /* Stats utilisateur */

    /**
     * Récupération des observations validées pour l'utilisateur classique
     *
     * @param $user
     * @return array
     */
    public function validatedObservationsByUser($user) {
        // Récupération des observations validées pour l'utilisateur classique
        $validatedObservationsByUser = $this->em->getRepository('AppBundle:Observation')->getValidatedObservationsByUser($user);
    
        return $validatedObservationsByUser;
    }

    /**
     * Récupération des Observations refusées pour l'utilisateur classique
     *
     * @param $user
     * @return array
     */
    public function refusedObservationsByUser($user) {
        // Récupération des Observations refusées pour l'utilisateur classique
        $refusedObservationsByUser = $this->em->getRepository('AppBundle:Observation')->getRefusedObservationsByUser($user);

        return $refusedObservationsByUser;
    }

    /**
     * Récupération des Observations refusées par l'utilisateur pro
     *
     * @param $user
     * @return array
     */
    public function refusedObservationsByValidator($user) {
        // Récupération des Observations refusées par l'utilisateur pro
        $refusedObservationsByValidator = $this->em->getRepository('AppBundle:Observation')->getRefusedObservationsByValidator($user);

        return $refusedObservationsByValidator;
    }

    /**
     * Récupération des Observations validées par l'utilisateur pro
     *
     * @param $user
     * @return array
     */
    public function validatedObservationsByValidator($user) {
        // Récupération des Observations validées par l'utilisateur pro
        $validatedObservationsByValidator = $this->em->getRepository('AppBundle:Observation')->getValidatedObservationsByValidator($user);

        return $validatedObservationsByValidator;
    }
    
    /**
     * Récupération de toutes les observations non validées
     *
     * @return Observation[]|array
     */
    public function getObservationsUnvalidated() {
        // Récupération de toutes observations existantes
        $observations = $this->em->getRepository('AppBundle:Observation')->findBy(array('validate' => null));
        // récupère le service knp paginator
        $paginator  = $this->container->get('knp_paginator');
        // retourne les observations paginées selon la page passée en get
        return $paginator->paginate(
            $observations, /* query NOT result */
            $this->request->getCurrentRequest()->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

    }

    /**
     * Récupération de toutes les observations
     *
     * @return Observation[]|array
     */
    public function getObservationsValidated() {
        // Récupération de toutes les observations validées
        $observations = $this->em->getRepository('AppBundle:Observation')->findBy(array('validate' => true));

        // Retourne les observations validées
        return $observations;
    }

    public function getSpeciesObserved() {
        // Récupération du nombre d'espèces observées
        $speciesObserved = $this->em->getRepository('AppBundle:Observation')->getSpeciesObserved();

        // Retourne le compteur des espèces observées
        return $speciesObserved;
    }

    /**
     * Récupération d'une observation par son id
     *
     * @param $id
     * @return Observation|null|object
     */
    public function getObservation($id) {
        // Récupération d'une observation par son id
        $observation = $this->em->getRepository('AppBundle:Observation')->find($id);

        // Retourne l'observation récupérée
        return $observation;
    }

    /**
     * Récupération des observations de l'utilisateur connecté
     *
     * @param $user
     * @return Observation[]|array
     */
    public function getObservationsByUser($user) {
        // Récupération des observation par utilisateur
        $observations = $this->em->getRepository('AppBundle:Observation')->findBy(array('observer' => $user));

        // Retourne les bbservations de l'utilisateur
        return $observations;
    }

    /**
     * Récupération du formulaire de saisie d'une observation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getObservationForm() {
        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create(CreateObservationType::class);

        // Retourne le formulaire
        return $form;
    }

    /**
     * Récupération du formulaire de validation d'une observation
     *
     * @param $id
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getObservationForValidationForm($id) {
        $observation = $this->getObservation($id);

        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create(ModifObservationType::class, $observation);

        // Retourne le formulaire
        return $form;
    }

    /**
     * Récupération du formulaire l'observation à modifier
     *
     * @param $id
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getObservationForModifyForm($id) {
        $observation = $this->getObservation($id);

        // Récupération du formulaire de saisie d'observaition
        $form = $this->formBuilder->create(ModifObservationByObserverType::class, $observation);

        // Retourne le formulaire
        return $form;
    }

    /**
     * Modification d'une observation
     *
     * @param Observation $observation
     */
    public function setUpdatedObservation(Observation $observation, $existingFile) {
        // Récupération du chemin du dossier de stockage
        $path = $this->container->getParameter('observations_directory');

        // Récupération du nouveau fichier
        $newFile = $observation->getPhotoPath();

        if ($newFile === null) {
            // Ajout de l'image par défault
            $observation->setPhotoPath($existingFile);

        } else {
            if ($existingFile != 'img/default/observation_default.png') {
                // Suppression de l'ancienne photo
                $this->filesystem->remove(array($existingFile));
            }

            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$newFile->guessExtension();

            // Déplacement du fichier dans le dossier des observations
            $newFile->move($path, $fileName);

            // Récupération du nouveau chemin
            $filePath = "uploads/observations_files/".$fileName;

            // Ajout des images
            $observation->setPhotoPath($filePath);
        }

        // Vérification du role de l'utilsateur qui modifie l'observation
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
            // Nouvelle date de validation suite à la modification par un pro ou un admin
            $observation->setValidateDate(new \DateTime());
        } else {
            // Nouvelle soumission à validation
            $observation->setValidate(null);
        }

        // Passage à true de la modification de l'observation
        $observation->setEditObservation(true);

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    /**
     * @param Observation $observation
     * @param User $user
     */
    public function setRefusedObservation(Observation $observation, User $user) {
        // Hydratation des valeurs de refus
        $observation->setValidateDate(new \DateTime());
        $observation->setValidate(false);
        $observation->setValidator($user);

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    /**
     * Acceptation de l'observation par un pro ou un admin
     *
     * @param Observation $observation
     * @param User $user
     */
    public function setAcceptedObservation(Observation $observation, User $user) {
        // Hydratation des valeurs d'acceptation
        $observation->setValidateDate(new \DateTime());
        $observation->setValidate(true);
        $observation->setValidator($user);

        // Remplissage du ordre et de la famille si il est null
        if ($observation->getType() === null && $observation->getFamily() === null) {
            $observation->setType($observation->getSpecies()->getType());
            $observation->setFamily($observation->getSpecies()->getFamily());
        }

        // Enregistrement
        $this->em->persist($observation);
        $this->em->flush();
    }

    /**
     * Ajout d'une nouvelle observation
     *
     * @param $observation
     * @param User $user
     */
    public function setNewObservation($observation, User $user) {
        // Création d'une nouvelle Observation
        $newObservation = new Observation();

        // Hydratation des valeurs
        $newObservation->setBirdNumber($observation['birdNumber']);
        $newObservation->setEggsNumber($observation['eggsNumber']);
        $newObservation->setEggsDescription($observation['eggsDescription']);
        $newObservation->setLatitude($observation['latitude']);
        $newObservation->setLongitude($observation['longitude']);
        $newObservation->setAltitude($observation['altitude']);
        $newObservation->setObservationDescription($observation['observationDescription']);

        // Définition de l'utilisateur créateur de l'observation
        $newObservation->setObserver($user);

        // Définition de la date de la nouvelle observation
        $newObservation->setObservationDate(new \DateTime());

        if ($observation['photoPath'] !== null) {
            // Récupération du fichier original
            $file = $observation['photoPath'];

            // Récupération du chemin du dossier de stockage
            $path = $this->container->getParameter('observations_directory');

            // Renommage du fichier
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Déplacement du fichier dans le dossiers des observations
            $file->move($path, $fileName);

            // Ajout de l'image dans l'observation
            $filePath = "uploads/observations_files/".$fileName;

            // Ajout des images
            $newObservation->setPhotoPath($filePath);
        }

        // Vérification si le nom commun ou le nom scientifique a été choisi
        if ($observation['vernacularName'] !== null) {
            $newObservation->setSpecies($observation['vernacularName']);
        } elseif ($observation['species'] !== null) {
            $newObservation->setSpecies($observation['species']);
        }

        if ($newObservation->getSpecies() !== null) {
            // Ajout du type dans l'observation
            $newObservation->setType($newObservation->getSpecies()->getType());

            // Ajout du type dans l'observation
            $newObservation->setFamily($newObservation->getSpecies()->getFamily());

            // Ajout de l'observation à l'espece
            $newObservation->getSpecies()->addObservation($newObservation);

            // Autovalidation pour les pro et admin
            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
                $newObservation->setValidate(true);
                $newObservation->setValidateDate(new \DateTime());
                $newObservation->setValidator($user);
            }
        }
        // Enregistrement
        $this->em->persist($newObservation);
        $this->em->flush();
    }
    public function getCurrentUserPaginatedObservationsList(User $user)
    {
        // récupère la liste des observations
        $observationList = $user->getObservations();
        // récupère le service knp paginator
        $paginator  = $this->container->get('knp_paginator');
        // retourne les observations paginées selon la page passée en get
        return $paginator->paginate(
            $observationList, /* query NOT result */
            $this->request->getCurrentRequest()->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
    }
    public function validateObservation(Observation $observation)
    {
        $errors = $this->validator->validate($observation);
        if (count($errors) > 0) {
            $errorsString = "";
            foreach ($errors as $error) {
                $errorsString .=$error->getmessage().'<br>';
            }
            return $errorsString;
        }
        return true;
    }
}
