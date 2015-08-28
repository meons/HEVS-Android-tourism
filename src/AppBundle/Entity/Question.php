<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuestionRepository")
 */
class Question
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
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz", inversedBy="questions")
     */
    private $quiz;

    /**
     * @var Answer
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Answer", mappedBy="nextQuestion")
     */
    private $previousAnswer;

    /**
     * @var Answer[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Answer", mappedBy="question", cascade={"persist", "remove"})
     */
    private $answers;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="questions")
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param string $text
     * @return Question
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
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz $quiz
     * @return Question
     */
    public function setQuiz(\AppBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \AppBundle\Entity\Quiz 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set previousAnswer
     *
     * @param \AppBundle\Entity\Answer $previousAnswer
     * @return Question
     */
    public function setPreviousAnswer(\AppBundle\Entity\Answer $previousAnswer = null)
    {
        $this->previousAnswer = $previousAnswer;
        $previousAnswer->setNextQuestion($this);

        return $this;
    }

    /**
     * Get previousAnswer
     *
     * @return \AppBundle\Entity\Answer
     */
    public function getPreviousAnswer()
    {
        return $this->previousAnswer;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Question
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add answers
     *
     * @param \AppBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\AppBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;
        $answers->setQuestion($this);

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \AppBundle\Entity\Answer $answers
     */
    public function removeAnswer(\AppBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
