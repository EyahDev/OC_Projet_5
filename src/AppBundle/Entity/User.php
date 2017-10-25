<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cette adresse email est déjà utilisé")
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est déjà utilisé")
 */
class User implements UserInterface, \Serializable
{

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="observer", cascade={"persist"})
     */
    private $observations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="validator", cascade={"persist"})
     */
    private $observationsValidated;

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
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Votre nom doit contenir au moins {{ limit }} caractères")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Votre prénom doit contenir au moins {{ limit }} caractères")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Votre nom d'utilisateur doit contenir au moins {{ limit }} caractères")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "L'adresse email '{{ value }}' n'est pas une adresse email valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Votre mot de passe doit contenir au moins {{ limit }} caractères")
     *  @Assert\Regex(
     *     pattern="/^(?=.*[a-zA-Z])(?=.*[0-9])/",
     *     match=true,
     *     message="Votre mot de passe doit contenir au moins une lettre et un chiffre."
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_path", type="string", length=255, nullable=true)
     */
    private $avatarPath;

    /**
     * @var bool
     *
     * @ORM\Column(name="proPermission", type="boolean")
     */
    private $proPermission;

    /**
     * @var bool
     *
     * @ORM\Column(name="newsletter", type="boolean")
     */
    private $newsletter;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array("ROLE_USER");

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="signupDate", type="datetime")
     */
    private $signupDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean")
     */
    private $enabled = true;

    public function eraseCredentials()
    {
    }

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
     * @return User
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return User
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set proPermission
     *
     * @param boolean $proPermission
     *
     * @return User
     */
    public function setProPermission($proPermission)
    {
        $this->proPermission = $proPermission;

        return $this;
    }

    /**
     * Get proPermission
     *
     * @return bool
     */
    public function getProPermission()
    {
        return $this->proPermission;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return User
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return bool
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set role
     *
     * @param array $role
     *
     * @return User
     */
    public function setRoles(Role $roles)
    {
        $this->roles = array($roles->getName());

        return $this;
    }

    /**
     * Get role
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->observationsValidated = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add observation
     *
     * @param \AppBundle\Entity\Observation $observation
     *
     * @return User
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
     * Add observationsValidated
     *
     * @param \AppBundle\Entity\Observation $observationsValidated
     *
     * @return User
     */
    public function addObservationsValidated(\AppBundle\Entity\Observation $observationsValidated)
    {
        $this->observationsValidated[] = $observationsValidated;

        return $this;
    }

    /**
     * Remove observationsValidated
     *
     * @param \AppBundle\Entity\Observation $observationsValidated
     */
    public function removeObservationsValidated(\AppBundle\Entity\Observation $observationsValidated)
    {
        $this->observationsValidated->removeElement($observationsValidated);
    }

    /**
     * Get observationsValidated
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservationsValidated()
    {
        return $this->observationsValidated;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
            ) = unserialize($serialized);
    }


    /**
     * Set signupDate
     *
     * @param \DateTime $signupDate
     *
     * @return User
     */
    public function setSignupDate($signupDate)
    {
        $this->signupDate = $signupDate;

        return $this;
    }

    /**
     * Get signupDate
     *
     * @return \DateTime
     */
    public function getSignupDate()
    {
        return $this->signupDate;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set avatarPath
     *
     * @param string $avatarPath
     *
     * @return User
     */
    public function setAvatarPath($avatarPath)
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    /**
     * Get avatarPath
     *
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }
}
