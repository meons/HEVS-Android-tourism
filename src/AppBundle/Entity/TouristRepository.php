<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TouristRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TouristRepository extends EntityRepository
{
    /**
     * @param $office Office
     * @return Tourist[]
     */
    public function findAllByOffice($office)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from('AppBundle:Tourist', 't')
            ->join('t.quizzes', 'q')
            ->join('q.office', 'o')
            ->where('o = :office')
            ->setParameter('office', $office);

        return $qb->getQuery()->getResult();
    }
}
