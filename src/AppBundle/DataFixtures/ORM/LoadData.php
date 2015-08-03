<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Category;
use AppBundle\Entity\Office;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData implements FixtureInterface, OrderedFixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for( $i = 0; $i < mt_rand(1,5); $i++ )
        {
            $office = new Office();
            $office->setName('Office '.$i);
            $manager->persist($office);

            for( $j = 0; $j < mt_rand(1,3); $j++ )
            {
                $quiz = new Quiz();
                $quiz->setOffice($office);
                $quiz->setName('Quiz '.$j.' belonging to '.$office->getName());
                $manager->persist($quiz);

                $categories = array();
                for ($c = 0; $c < 5; $c++) {
                    $category = new Category();
                    $category->setName('Category '.$c);
                    $category->setQuiz($quiz);
                    $categories[] = $category;
                    $manager->persist($category);
                }

                $this->createQuestions($manager, $quiz, $categories);
            }
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param $quiz Quiz
     * @param $categories Category[]
     * @param int $depth
     * @param Answer|null $previousAnswer
     */
    private function createQuestions($manager, $quiz, $categories, $depth = 3, $previousAnswer = null)
    {
        if ($depth === 0) {
            return;
        }

        $question = new Question();
        $question->setText('Question ?');
        $question->setQuiz($quiz);
        $question->setCategory($categories[rand(0, count($categories) - 1)]);
        $manager->persist($question);

        if ($previousAnswer !== null) {
            $previousAnswer->setNextQuestion($question);
            $manager->persist($previousAnswer);
        }

        for ($a = 0; $a < mt_rand(2, 3); $a++) {
            $answer = new Answer();
            $answer->setText('Answer '.$a);
            $answer->setDescription('Description');
            $answer->setScore(mt_rand(-5, 5));
            $answer->setQuestion($question);
            $manager->persist($answer);

            $newDepth = $depth - rand(1, $depth - 1);
            $this->createQuestions($manager, $quiz, $categories, $newDepth, $answer);
        }
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}