<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tourist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TouristRepository")
 */
class Tourist
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
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Result", mappedBy="tourist")
     */
    private $result;

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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Quiz", mappedBy="tourists")
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
     * Set reference
     *
     * @param string $reference
     * @return Tourist
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string 
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set result
     *
     * @param \AppBundle\Entity\Result $result
     * @return Tourist
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
     * Add quizzes
     *
     * @param \AppBundle\Entity\Quiz $quizzes
     * @return Tourist
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
