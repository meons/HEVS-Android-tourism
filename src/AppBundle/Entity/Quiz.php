<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuizRepository")
 */
class Quiz
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
     * @var Office
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office", inversedBy="quizzes")
     */
    private $office;

    /**
     * Question[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz", mappedBy="quiz")
     */
    private $questions;

    /**
     * @var Tourist[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tourist", inversedBy="quizzes")
     * @ORM\JoinTable(name="quiz_tourist")
     */
    private $tourists;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set office
     *
     * @param \AppBundle\Entity\Office $office
     * @return Quiz
     */
    public function setOffice(\AppBundle\Entity\Office $office = null)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * Get office
     *
     * @return \AppBundle\Entity\Office 
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Add questions
     *
     * @param \AppBundle\Entity\Quiz $questions
     * @return Quiz
     */
    public function addQuestion(\AppBundle\Entity\Quiz $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \AppBundle\Entity\Quiz $questions
     */
    public function removeQuestion(\AppBundle\Entity\Quiz $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add tourists
     *
     * @param \AppBundle\Entity\Tourist $tourists
     * @return Quiz
     */
    public function addTourist(\AppBundle\Entity\Tourist $tourists)
    {
        $this->tourists[] = $tourists;

        return $this;
    }

    /**
     * Remove tourists
     *
     * @param \AppBundle\Entity\Tourist $tourists
     */
    public function removeTourist(\AppBundle\Entity\Tourist $tourists)
    {
        $this->tourists->removeElement($tourists);
    }

    /**
     * Get tourists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTourists()
    {
        return $this->tourists;
    }
}