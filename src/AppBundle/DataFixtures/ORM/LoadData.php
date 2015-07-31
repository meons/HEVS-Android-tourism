<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Category;
use AppBundle\Entity\Office;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData implements FixtureInterface {

    const NUMBER_OF_OFFICES = 3;
    const NUMBER_OF_QUIZZES = 2;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for( $i = 0; $i < self::NUMBER_OF_OFFICES; $i++ )
        {
            $office = new Office();
            $office->setName('Office '.$i);
            $manager->persist($office);

            for( $j = 0; $j < self::NUMBER_OF_QUIZZES; $j++)
            {
                $quiz = new Quiz();
                $quiz->setOffice($office);
                $manager->persist($quiz);

                $answers = array();

                for( $k = 0; $k < 3; $k++)
                {
                    $category = new Category();
                    $category->setName('Category '.$k.' from quiz ID '.$quiz->getId());
                    $category->setQuiz($quiz);

                    for( $l = 0; $l < 10; $l++)
                    {
                        $answer = new Answer();
                        $answer->setText('Answer');
                        $answer->setDescription('Description');
                        $answers[] = $answer;

                        $question = new Question();
                        $question->setText('Question '.$l);
                        $question->setQuiz($quiz);
                        $question->setCategory($category);
                        $question->addAnswer($answer);
                    }
                }

                foreach( $answers  as $key => $answer)
                {
                    if($key == count($answers - 1))
                    {
                        break;
                    }

                    $answer->setNextQuestion($answers[$key + 1]);
                }
            }
        }

        $manager->flush();
    }
}