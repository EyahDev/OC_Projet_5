<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Observation
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 */
class Observation
{
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
     */
    private $birdNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="eggsNumber", type="integer")
     */
    private $eggsNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="eggsDescription", type="string", length=255, nullable=true)
     */
    private $eggsDescription;

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
     * @ORM\Column(name="altitude", type="string", length=255)
     */
    private $altitude;

    /**
     * @var string
     *
     * @ORM\Column(name="photoPath", type="string", length=255)
     */
    private $photoPath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="observationDate", type="date")
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
     * @ORM\Column(name="validateDate", type="date")
     */
    private $validateDate;


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
}

