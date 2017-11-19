<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Species
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpeciesRepository")
 */
class Species
{
    /**
     * @var SpeciesType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SpeciesType", inversedBy="species")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SpeciesFamily", inversedBy="species")
     */
    private $family;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="species", cascade={"persist"})
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
     * @ORM\Column(name="referenceName", type="string", length=255)
     */
    private $referenceName;

    /**
     * @var string
     *
     * @ORM\Column(name="vernacularName", type="string", length=255, nullable=true)
     */
    private $vernacularName;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"referenceName"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;


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
     * Set referenceName
     *
     * @param string $referenceName
     *
     * @return Species
     */
    public function setReferenceName($referenceName)
    {
        $this->referenceName = $referenceName;

        return $this;
    }

    /**
     * Get referenceName
     *
     * @return string
     */
    public function getReferenceName()
    {
        return $this->referenceName;
    }

    /**
     * Set vernacularName
     *
     * @param string $vernacularName
     *
     * @return Species
     */
    public function setVernacularName($vernacularName)
    {
        $this->vernacularName = $vernacularName;

        return $this;
    }

    /**
     * Get vernacularName
     *
     * @return string
     */
    public function getVernacularName()
    {
        return $this->vernacularName;
    }


    /**
     * Set description
     *
     * @param string $description
     *
     * @return Species
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return Species
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

    /**
     * Set type
     *
     * @param \AppBundle\Entity\SpeciesType $type
     *
     * @return Species
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
     * Set family
     *
     * @param \AppBundle\Entity\SpeciesFamily $family
     *
     * @return Species
     */
    public function setFamily(\AppBundle\Entity\SpeciesFamily $family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return \AppBundle\Entity\SpeciesFamily
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Species
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
