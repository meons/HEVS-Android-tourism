<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AnswerRepository")
 */
class Answer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var Question
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Question", inversedBy="previousAnswer")
     */
    private $nextQuestion;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="answers")
     */
    private $question;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Result", mappedBy="answer")
     **/
    private $result;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Answer
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Answer
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
     * Set score
     *
     * @param int $score
     * @return Answer
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set nextQuestion
     *
     * @param \AppBundle\Entity\Question $nextQuestion
     * @return Answer
     */
    public function setNextQuestion(\AppBundle\Entity\Question $nextQuestion = null)
    {
        $this->nextQuestion = $nextQuestion;

        return $this;
    }

    /**
     * Get nextQuestion
     *
     * @return \AppBundle\Entity\Question 
     */
    public function getNextQuestion()
    {
        return $this->nextQuestion;
    }

    /**
     * Set result
     *
     * @param \AppBundle\Entity\Result $result
     * @return Answer
     */
    public function setResult(\AppBundle\Entity\Result $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return \AppBundle\Entity\Result 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set question
     *
     * @param \AppBundle\Entity\Question $question
     * @return Answer
     */
    public function setQuestion(\AppBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \AppBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
