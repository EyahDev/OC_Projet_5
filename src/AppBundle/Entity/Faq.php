<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Faq
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FaqRepository")
 */
class Faq
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
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir une question valide.")
     * @Assert\Length(
     *     min="2",
     *     max="255",
     *     minMessage="Votre question doit comporter au minimun {{ limit }} caractères.",
     *     maxMessage="Votre question doit comporter au maximun {{ limit }} caractères."
     * )
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text")
     *
     * @Assert\NotBlank(message="Le champ réponse ne peut pas être vide.")
     * @Assert\Length(
     *     min="2",
     *     max="255",
     *     minMessage="Votre réponse doit comporter au minimun {{ limit }} caractères.",
     *     maxMessage="Votre réponse doit comporter au maximun {{ limit }} caractères."
     * )
     */
    private $answer;


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
     * Set question
     *
     * @param string $questions
     *
     * @return Faq
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return Faq
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
