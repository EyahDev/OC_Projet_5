<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator as CustomAssert;

/**
 * Observation
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 */
class Observation
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Species", inversedBy="observations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $species;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SpeciesType", inversedBy="observations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SpeciesFamily", inversedBy="observations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $family;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="observations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $observer;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="observationsValidated")
     * @ORM\JoinColumn(nullable=true)
     */
    private $validator;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="birdNumber", type="integer")
     * @Assert\GreaterThanOrEqual(
     *     value="1",
     *     message="Veuillez saisir un nombre d'oiseaux observés valide."
     * )
     * @Assert\NotBlank(message="Veuillez saisir un nombre d'oiseaux observés valide.")
     */
    private $birdNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="eggsNumber", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(
     *     value="0",
     *     message="Veuillez saisir un nombre d'oeufs observés valide."
     * )
     */
    private $eggsNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="eggsDescription", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min="2",
     *     max="255",
     *     minMessage="Votre description des oeufs doit comporter minimun {{ limit }} caratères.",
     *     maxMessage="Votre description des oeufs doit comporter maximun {{ limit }} caratères.",
     * )
     */
    private $eggsDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="observationDescription", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min="2",
     *     max="255",
     *     minMessage="Votre description de l'observation doit comporter minimun {{ limit }} caratères.",
     *     maxMessage="Votre description de l'observation doit comporter maximun {{ limit }} caratères.",
     * )
     */
    private $observationDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="altitude", type="string", length=255, nullable=true)
     */
    private $altitude;

    /**
     * @var string
     *
     * @ORM\Column(name="photoPath", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     maxSize="4M",
     *     minWidth="173",
     *     minHeight="165",
     *     maxSizeMessage="Votre photo doit ne peut pas faire plus de 4Mo.",
     *     minHeightMessage="Votre photo doit faire minimun 173x165px. (Hauteur de {{ height }}px actuellement)",
     *     minWidthMessage="Votre photo doit faire minimun 173x165px. (Largeur de {{ width }}px actuellement)",
     * )
     */
    private $photoPath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="observationDate", type="datetime")
     */
    private $observationDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="validate", type="boolean", nullable=true)
     */
    private $validate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validateDate", type="datetime", nullable=true)
     */
    private $validateDate;

    /**
     * @var string
     *
     * @ORM\Column(name="validation_comment", type="string", length=255, nullable=true)
     */
    private $validationComment;

    /**
     * @var boolean
     *
     * @ORM\Column(name="edit_observation", type="boolean", nullable=true)
     */
    private $editObservation;

    /**
     * @var integer
     *
     * @ORM\Column(name="see_too", type="integer", nullable=true)
     */
    private $seeToo;


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
     * Set birdNumber
     *
     * @param integer $birdNumber
     *
     * @return Observation
     */
    public function setBirdNumber($birdNumber)
    {
        $this->birdNumber = $birdNumber;

        return $this;
    }

    /**
     * Get birdNumber
     *
     * @return int
     */
    public function getBirdNumber()
    {
        return $this->birdNumber;
    }

    /**
     * Set eggsNumber
     *
     * @param integer $eggsNumber
     *
     * @return Observation
     */
    public function setEggsNumber($eggsNumber)
    {
        $this->eggsNumber = $eggsNumber;

        return $this;
    }

    /**
     * Get eggsNumber
     *
     * @return int
     */
    public function getEggsNumber()
    {
        return $this->eggsNumber;
    }

    /**
     * Set eggsDescription
     *
     * @param string $eggsDescription
     *
     * @return Observation
     */
    public function setEggsDescription($eggsDescription)
    {
        $this->eggsDescription = $eggsDescription;

        return $this;
    }

    /**
     * Get eggsDescription
     *
     * @return string
     */
    public function getEggsDescription()
    {
        return $this->eggsDescription;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set altitude
     *
     * @param string $altitude
     *
     * @return Observation
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * Get altitude
     *
     * @return string
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * Set photoPath
     *
     * @param string $photoPath
     *
     * @return Observation
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    /**
     * Get photoPath
     *
     * @return string
     */
    public function getPhotoPath()
    {
        return $this->photoPath;
    }

    /**
     * Set observationDate
     *
     * @param \DateTime $observationDate
     *
     * @return Observation
     */
    public function setObservationDate($observationDate)
    {
        $this->observationDate = $observationDate;

        return $this;
    }

    /**
     * Get observationDate
     *
     * @return \DateTime
     */
    public function getObservationDate()
    {
        return $this->observationDate;
    }

    /**
     * Set validate
     *
     * @param boolean $validate
     *
     * @return Observation
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get validate
     *
     * @return bool
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set validateDate
     *
     * @param \DateTime $validateDate
     *
     * @return Observation
     */
    public function setValidateDate($validateDate)
    {
        $this->validateDate = $validateDate;

        return $this;
    }

    /**
     * Get validateDate
     *
     * @return \DateTime
     */
    public function getValidateDate()
    {
        return $this->validateDate;
    }

    /**
     * Set species
     *
     * @param \AppBundle\Entity\Species $species
     *
     * @return Observation
     */
    public function setSpecies(\AppBundle\Entity\Species $species = null)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return \AppBundle\Entity\Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set observer
     *
     * @param \AppBundle\Entity\User $observer
     *
     * @return Observation
     */
    public function setObserver(\AppBundle\Entity\User $observer)
    {
        $this->observer = $observer;

        return $this;
    }

    /**
     * Get observer
     *
     * @return \AppBundle\Entity\User
     */
    public function getObserver()
    {
        return $this->observer;
    }

    /**
     * Set validator
     *
     * @param \AppBundle\Entity\User $validator
     *
     * @return Observation
     */
    public function setValidator(\AppBundle\Entity\User $validator = null)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Get validator
     *
     * @return \AppBundle\Entity\User
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Set observationDescription
     *
     * @param string $observationDescription
     *
     * @return Observation
     */
    public function setObservationDescription($observationDescription)
    {
        $this->observationDescription = $observationDescription;

        return $this;
    }

    /**
     * Get observationDescription
     *
     * @return string
     */
    public function getObservationDescription()
    {
        return $this->observationDescription;
    }

    /**
     * Set validationComment
     *
     * @param string $validationComment
     *
     * @return Observation
     */
    public function setValidationComment($validationComment)
    {
        $this->validationComment = $validationComment;

        return $this;
    }

    /**
     * Get validationComment
     *
     * @return string
     */
    public function getValidationComment()
    {
        return $this->validationComment;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\SpeciesType $type
     *
     * @return Observation
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
     * @return Observation
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
     * Set editObservation
     *
     * @param boolean $editObservation
     *
     * @return Observation
     */
    public function setEditObservation($editObservation)
    {
        $this->editObservation = $editObservation;

        return $this;
    }

    /**
     * Get editObservation
     *
     * @return boolean
     */
    public function getEditObservation()
    {
        return $this->editObservation;
    }

    /**
     * Set seeToo
     *
     * @param integer $seeToo
     *
     * @return Observation
     */
    public function setSeeToo($seeToo)
    {
        $this->seeToo = $seeToo;

        return $this;
    }

    /**
     * Get seeToo
     *
     * @return integer
     */
    public function getSeeToo()
    {
        return $this->seeToo;
    }
}
