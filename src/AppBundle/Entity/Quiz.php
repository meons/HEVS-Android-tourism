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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Quiz", mappedBy="quiz")
     */
    private $questions;

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
}
