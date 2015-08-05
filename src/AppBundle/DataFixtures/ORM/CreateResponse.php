<?php
/**
 * Created by PhpStorm.
 * User: Vince
 * Date: 02.08.2015
 * Time: 09:32
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Answer;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\AnsiQuoteStrategy;
use Proxies\__CG__\AppBundle\Entity\Category;
use Proxies\__CG__\AppBundle\Entity\Question;
use Proxies\__CG__\AppBundle\Entity\Result;
use Proxies\__CG__\AppBundle\Entity\Tourist;

class CreateResponse implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @param $q Question
     */
    public function load(ObjectManager $manager)
    {
        $quizzes = $manager->getRepository('AppBundle:Quiz')->findAll();

        // create some tourists
        for ( $i = 0; $i < mt_rand(2, 5); $i++ ) {
            $tourist = new Tourist();
            $tourist->setReference('Ref. T'.$i);
            $manager->persist($tourist);

            // tourist respond to arbitrary quizzes, not all but at least one
            $randQuizzesKeys = (array)array_rand($quizzes, mt_rand(1, count($quizzes)));

            // loop through quizzes random keys to get single quiz
            for ($j = 0; $j < count($randQuizzesKeys); $j++) {
                $quiz = $quizzes[$randQuizzesKeys[$j]];

                $quiz->addTourist($tourist);
                $tourist->addQuiz($quiz);

                // get tourist response
                //$this->respond($manager, $tourist, $quiz->getQuestions()[0]);

                // respond to all categories
                /** @var  $category Category */
                foreach ($quiz->getCategories() as $category) {
                    $this->respond($manager, $tourist, $category->getQuiz()->getQuestions()[0]);
                }
            }
        }

        $manager->flush();
    }

    /**
     * @param $manager ObjectManager
     * @param $tourist Tourist
     * @param $q Question
     */
    private function respond($manager, $tourist, $q)
    {
        if ($q === null) {
            return;
        }


        /*
         * get all possible answers for this question
         * then choose one arbitrary
         */
        $answers = $q->getAnswers();
        $a = $answers[mt_rand(0, count( $answers ) - 1)];

        $result = new Result();
        $result->setAnswer($a);
        $result->setTourist($tourist);
        $result->setQuiz($q->getQuiz());
        $manager->persist($result);

        // echo "{$q->getText()} : {$a->getText()} -> ";

        // repeat...
        $q = $a->getNextQuestion();
        $this->respond($manager, $tourist, $q);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}