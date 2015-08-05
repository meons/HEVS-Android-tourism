<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Office
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Office", inversedBy="users")
     */
    private $office;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set office
     *
     * @param \AppBundle\Entity\Office $office
     * @return User
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