<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Office
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OfficeRepository")
 */
class Office
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quizzes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var Quiz[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz", mappedBy="quiz")
     */
    private $quizzes;

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
     * Set name
     *
     * @param string $name
     * @return Office
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
     * Add quizzes
     *
     * @param \AppBundle\Entity\Quiz $quizzes
     * @return Office
     */
    public function addQuiz(\AppBundle\Entity\Quiz $quizzes)
    {
        $this->quizzes[] = $quizzes;

        return $this;
    }

    /**
     * Remove quizzes
     *
     * @param \AppBundle\Entity\Quiz $quizzes
     */
    public function removeQuiz(\AppBundle\Entity\Quiz $quizzes)
    {
        $this->quizzes->removeElement($quizzes);
    }

    /**
     * Get quizzes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }
}
