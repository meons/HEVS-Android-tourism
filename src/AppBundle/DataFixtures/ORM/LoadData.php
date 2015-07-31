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

                $questions = array();
                $answers = array();

                for( $k = 0; $k < mt_rand(1,5); $k++ )
                {
                    $category = new Category();
                    $category->setName('Category '.$k.' belonging to '.$quiz->getName());
                    $category->setQuiz($quiz);
                    $manager->persist($category);


                    for( $l = 0; $l < mt_rand(1,5); $l++ )
                    {
                        $answer = new Answer();
                        $answer->setText('Answer '.$l.' belonging to '.$category->getName());
                        $answer->setDescription('Description from answer '.$l);
                        $answer->setScore($l);

                        $answers[] = $answer;

                        $manager->persist($answer);

                        $question = new Question();
                        $question->setText('Question '.$l.' belonging to '.$category->getName());
                        $question->setQuiz($quiz);
                        $question->setCategory($category);
                        $question->addAnswer($answer);


                        $manager->persist($question);
                        $questions[] = $question;
                    }
                }

                foreach( $answers  as $key => $answer )
                {
                    $answer->setQuestion($questions[$key]);

                    if($key == count($questions) - 1)
                    {
                        break;
                    }

                    $answer->setNextQuestion($questions[$key + 1]);
                }

                /*
                foreach( $questions as $key => $question )
                {
                    if($key != 0)
                    {
                        $question->setPreviousAnswer($answers[$key - 1]);
                    }
                }
                */
            }
        }

        $manager->flush();
    }
}