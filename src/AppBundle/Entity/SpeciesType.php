<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpeciesType
 *
 * @ORM\Table(name="species_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesTypeRepository")
 */
class SpeciesType
{
    /**
     * @var Species
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Species", mappedBy="type", cascade={"persist"})
     *
     */
    private $species;

    /**
     * @var SpeciesFamily
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SpeciesFamily", mappedBy="type", cascade={"persist"})
     *
     */
    private $families;

     /**
     * @var Observation
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="type", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $observations;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SpeciesType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->species = new \Doctrine\Common\Collections\ArrayCollection();
        $this->families = new \Doctrine\Common\Collections\ArrayCollection();
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add species
     *
     * @param \AppBundle\Entity\Species $species
     *
     * @return SpeciesType
     */
    public function addSpecy(\AppBundle\Entity\Species $species)
    {
        $this->species[] = $species;

        return $this;
    }

    /**
     * Remove species
     *
     * @param \AppBundle\Entity\Species $species
     */
    public function removeSpecy(\AppBundle\Entity\Species $species)
    {
        $this->species->removeElement($species);
    }

    /**
     * Get species
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Add family
     *
     * @param \AppBundle\Entity\SpeciesFamily $family
     *
     * @return SpeciesType
     */
    public function addFamily(\AppBundle\Entity\SpeciesFamily $family)
    {
        $this->families[] = $family;

        return $this;
    }

    /**
     * Remove family
     *
     * @param \AppBundle\Entity\SpeciesFamily $family
     */
    public function removeFamily(\AppBundle\Entity\SpeciesFamily $family)
    {
        $this->families->removeElement($family);
    }

    /**
     * Get families
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFamilies()
    {
        return $this->families;
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return SpeciesType
     */
    public function addObservation(\AppBundle\Entity\Observation $observation)
    {
        $this->observations[] = $observation;

        return $this;
    }

    /**
     * Remove observation
     *
     * @param \AppBundle\Entity\Observation $observation
     */
    public function removeObservation(\AppBundle\Entity\Observation $observation)
    {
        $this->observations->removeElement($observation);
    }

    /**
     * Get observations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservations()
    {
        return $this->observations;
    }
}
