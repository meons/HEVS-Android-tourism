<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recommendation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RecommendationRepository")
 */
class Recommendation
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
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quiz", inversedBy="recommendations")
     */
    private $quiz;

    /**
     * @var RecommendationCriteria[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RecommendationCriteria", mappedBy="recommendation", cascade={"persist", "remove"})
     */
    private $recommendationCriterias;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="recommendations")
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
     * Set name
     *
     * @param string $name
     * @return Recommendation
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
     * Constructor
     */
    public function __construct()
    {
        $this->recommendationCriterias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set quiz
     *
     * @param \AppBundle\Entity\Quiz $quiz
     * @return Recommendation
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
     * Add recommendationCriterias
     *
     * @param \AppBundle\Entity\RecommendationCriteria $recommendationCriterias
     * @return Recommendation
     */
    public function addRecommendationCriteria(\AppBundle\Entity\RecommendationCriteria $recommendationCriterias)
    {
        $this->recommendationCriterias[] = $recommendationCriterias;

        return $this;
    }

    /**
     * Remove recommendationCriterias
     *
     * @param \AppBundle\Entity\RecommendationCriteria $recommendationCriterias
     */
    public function removeRecommendationCriteria(\AppBundle\Entity\RecommendationCriteria $recommendationCriterias)
    {
        $this->recommendationCriterias->removeElement($recommendationCriterias);
    }

    /**
     * Get recommendationCriterias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecommendationCriterias()
    {
        return $this->recommendationCriterias;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Recommendation
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
}
