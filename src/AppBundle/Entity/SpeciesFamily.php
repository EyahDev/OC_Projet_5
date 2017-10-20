<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpeciesFamily
 *
 * @ORM\Table(name="species_family")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesFamilyRepository")
 */
class SpeciesFamily
{
    /**
     * @var Species
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Species", mappedBy="family")
     *
     */
    private $species;

    /**
     * @var SpeciesType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SpeciesType", inversedBy="families")
     *
     */
    private $type;

    /**
     * @var Observation
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="family", cascade={"persist"})
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
     * @return SpeciesFamily
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
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add species
     *
     * @param \AppBundle\Entity\Species $species
     *
     * @return SpeciesFamily
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
     * Set type
     *
     * @param \AppBundle\Entity\SpeciesType $type
     *
     * @return SpeciesFamily
     */
    public function setType(\AppBundle\Entity\SpeciesType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\SpeciesType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return SpeciesFamily
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
