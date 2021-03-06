<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * QuizRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuizRepository extends EntityRepository
{
    /**
     * @param $office Office
     * @return Quiz[]
     */
    public function findAllByOffice($office)
    {
        return $this->findBy(array('office' => $office));
    }

    /**
     * @param $quiz Quiz|int
     * @param $tourist Tourist|int
     * @return Quiz
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByTourist($quiz, $tourist)
    {
        return $this->createQueryBuilder('q')
            ->addSelect('p')->addSelect('t')->addSelect('r')
            ->join('q.participations', 'p')
            ->join('p.tourist', 't')
            ->join('t.results', 'r')
            ->where('q.id = :quiz')->setParameter('quiz', $quiz)
            ->andWhere('t.id = :tourist')->setParameter('tourist', $tourist)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $participation Participation|int
     * @return Quiz
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByParticipation($participation)
    {
        return $this->createQueryBuilder('q')
            ->addSelect('p')
            ->join('q.participations', 'p')
            ->where('p.id = :participation')->setParameter('participation', $participation)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
