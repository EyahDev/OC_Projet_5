<?php

namespace AppBundle\Repository;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends \Doctrine\ORM\EntityRepository
{
    /* Récupération des observations par mois */


    /**
     * Récupération de l'année en cours
     *
     * @return string
     */
    public function getCurrentYear() {
        // Création de la date du jour
        $date = new \DateTime();

        //Retourne l'année en cours
        return $date->format('Y');
    }

    /**
     * Janvier
     *
     * @param $slug
     * @return array
     */
    public function getObservationForJan($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-01-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-01-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Frevier
     *
     * @param $slug
     * @return array
     */
    public function getObservationForFev($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-02-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-02-29'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Mars
     *
     * @param $slug
     * @return array
     */
    public function getObservationForMarch($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-03-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-03-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Avril
     *
     * @param $slug
     * @return array
     */
    public function getObservationForApril($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-04-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-04-30'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * mai
     *
     * @param $slug
     * @return array
     */
    public function getObservationForMay($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-05-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-05-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Juin
     *
     * @param $slug
     * @return array
     */
    public function getObservationForJune($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-06-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-06-30'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Juillet
     *
     * @param $slug
     * @return array
     */
    public function getObservationForJuly($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-07-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-07-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Aout
     *
     * @param $slug
     * @return array
     */
    public function getObservationForAug($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-08-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-08-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Septembre
     *
     * @param $slug
     * @return array
     */
    public function getObservationForSept($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-09-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-09-30'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Octobre
     *
     * @param $slug
     * @return array
     */
    public function getObservationForOct($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-10-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-10-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Novembre
     *
     * @param $slug
     * @return array
     */
    public function getObservationForNov($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-11-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-11-30'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }

    /**
     * Decembre
     *
     * @param $slug
     * @return array
     */
    public function getObservationForDec($slug){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        // Récupération des observations pour le mois
        $qb->leftJoin('o.species', 's')
            ->where('s.slug = :slug')
            ->andWhere('o.observationDate BETWEEN :start AND :end')
            ->andWhere('o.validate = true')
            ->setParameters(array(
                'slug' => $slug,
                'start' => new \DateTime($this->getCurrentYear() . '-12-01'),
                'end' => new \DateTime($this->getCurrentYear() . '-12-31'),
            ));

        // Retourne le resultat
        return $qb->getQuery()->getResult();
    }



    /* Recherche Maps */

    public function getObservationBySpecies($criteria){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        if ($criteria['begin'] == null) {
            $qb->where('o.species = :criteria')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameter('criteria', $criteria['reference']);
        } else {
            $qb->where('o.species = :criteria')
                ->andWhere('o.observationDate BETWEEN :start AND :end ')
                ->andWhere('o.validate = true')
                ->setParameters(array(
                    'criteria' => $criteria['reference'],
                    'start' => $criteria['begin'],
                    'end' => $criteria['end']
                ));
        }

        return $qb->getQuery()->getResult();
    }

    public function getObservationByVernacularName($criteria){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        if ($criteria['begin'] == null) {
            $qb->where('o.species = :criteria')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameter('criteria', $criteria['vernacular']);
        } else {
            $qb->where('o.species = :criteria')
                ->andWhere('o.observationDate BETWEEN :start AND :end ')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameters(array(
                    'criteria' => $criteria['vernacular'],
                    'start' => $criteria['begin'],
                    'end' => $criteria['end']
                ));
        }
        return $qb->getQuery()->getResult();
    }

    public function getObservationByType($criteria){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        if ($criteria['begin'] == null) {
            $qb->where('o.type = :criteria')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameter('criteria', $criteria['type']);
        } else {
            $qb->where('o.type = :criteria')
                ->andWhere('o.observationDate BETWEEN :start AND :end ')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameters(array(
                    'criteria' => $criteria['type'],
                    'start' => $criteria['begin'],
                    'end' => $criteria['end']
                ));
        }

        return $qb->getQuery()->getResult();
    }

    public function getObservationByFamily($criteria){
        // Création de l'alias
        $qb = $this->createQueryBuilder('o');

        if ($criteria['begin'] == null) {
            $qb->where('o.family = :criteria')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameter('criteria', $criteria['family']);
        } else {
            $qb->where('o.family = :criteria')
                ->andWhere('o.observationDate BETWEEN :start AND :end ')
                ->andWhere('o.validate = true')
                ->orderBy('o.observationDate', 'DESC')
                ->setParameters(array(
                    'criteria' => $criteria['family'],
                    'start' => $criteria['begin'],
                    'end' => $criteria['end']
                ));
        }

        return $qb->getQuery()->getResult();
    }

    /* Statistiques du dashboard */

    public function getValidatedObservationsByUser($user)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.validate = :validate')
            ->andWhere('o.observer = :user')
            ->setParameters(array(
                'validate' => 1,
                'user' => $user));

        return $qb
            ->getQuery()
            ->getResult();
    }
    

    public function getRefusedObservationsByUser($user)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.validate = :validate')
            ->andWhere('o.observer = :user')
            ->setParameters(array(
                'validate' => 0,
                'user' => $user));
        
        return $qb
            ->getQuery()
            ->getResult();
        
    } public function getValidatedObservationsByValidator($user)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.validate = :validate')
            ->andWhere('o.validator = :user')
            ->setParameters(array(
                'validate' => 1,
                'user' => $user));
        
        return $qb
            ->getQuery()
            ->getResult();
    }
    
     public function getRefusedObservationsByValidator($user)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.validate = :validate')
            ->andWhere('o.validator = :user')
            ->setParameters(array(
                'validate' => 0,
                'user' => $user));
        
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getSpeciesObserved()
    {
        $qb = $this->createQueryBuilder('o')
            ->select('COUNT(DISTINCT o.species)');

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
    
}
