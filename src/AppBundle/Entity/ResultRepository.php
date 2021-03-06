<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ResultRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResultRepository extends EntityRepository
{
    /**
     * @param $participation
     * @return Result[]
     */
    public function getAllByParticipation($participation)
    {
        return $this->findBy(array('participation' => $participation));
    }
}
