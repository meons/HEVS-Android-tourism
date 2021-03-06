<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecommendationCriteria
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RecommendationCriteriaRepository")
 */
class RecommendationCriteria
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
     * @var integer
     *
     * @ORM\Column(name="threshold_min", type="integer")
     */
    private $thresholdMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="threshold_max", type="integer")
     */
    private $thresholdMax;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var Recommendation
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Recommendation", inversedBy="recommendationCriterias")
     */
    private $recommendation;

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
     * Set thresholdMin
     *
     * @param integer $thresholdMin
     * @return RecommendationCriteria
     */
    public function setThresholdMin($thresholdMin)
    {
        $this->thresholdMin = $thresholdMin;

        return $this;
    }

    /**
     * Get thresholdMin
     *
     * @return integer 
     */
    public function getThresholdMin()
    {
        return $this->thresholdMin;
    }

    /**
     * Set thresholdMax
     *
     * @param integer $thresholdMax
     * @return RecommendationCriteria
     */
    public function setThresholdMax($thresholdMax)
    {
        $this->thresholdMax = $thresholdMax;

        return $this;
    }

    /**
     * Get thresholdMax
     *
     * @return integer 
     */
    public function getThresholdMax()
    {
        return $this->thresholdMax;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return RecommendationCriteria
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set recommendation
     *
     * @param \AppBundle\Entity\Recommendation $recommendation
     * @return RecommendationCriteria
     */
    public function setRecommendation(\AppBundle\Entity\Recommendation $recommendation = null)
    {
        $this->recommendation = $recommendation;

        return $this;
    }

    /**
     * Get recommendation
     *
     * @return \AppBundle\Entity\Recommendation 
     */
    public function getRecommendation()
    {
        return $this->recommendation;
    }
}
