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
     * @var \DateTime
     * @ORM\Column(type="datetime", name="creation_date")
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Result", mappedBy="tourist")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participation", mappedBy="tourist", cascade={"persist"})
     */
    private $participations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->results = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Tourist
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Get answers result
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAnswersResult()
    {
        $answers = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getResults() as $result) {
            $answers->add($result->getAnswer());
        }

        return $answers;
    }

    /**
     * Add results
     *
     * @param \AppBundle\Entity\Result $results
     * @return Tourist
     */
    public function addResult(\AppBundle\Entity\Result $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \AppBundle\Entity\Result $results
     */
    public function removeResult(\AppBundle\Entity\Result $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Add participations
     *
     * @param \AppBundle\Entity\Participation $participations
     * @return Tourist
     */
    public function addParticipation(\AppBundle\Entity\Participation $participations)
    {
        $this->participations[] = $participations;

        return $this;
    }

    /**
     * Remove participations
     *
     * @param \AppBundle\Entity\Participation $participations
     */
    public function removeParticipation(\AppBundle\Entity\Participation $participations)
    {
        $this->participations->removeElement($participations);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipations()
    {
        return $this->participations;
    }
}
