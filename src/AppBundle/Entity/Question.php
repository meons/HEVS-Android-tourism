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
    private $answer;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="questions")
     */
    private $category;

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
}
