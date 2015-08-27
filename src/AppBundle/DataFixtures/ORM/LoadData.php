<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Category;
use AppBundle\Entity\Office;
use AppBundle\Entity\Participation;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\Tourist;
use AppBundle\Entity\Result;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++ ) {
            $office = new Office();
            $office->setName('Office '.$i);
            $manager->persist($office);

            for ($j = 0; $j < mt_rand(1,5); $j++ ) {
                $quiz = new Quiz();
                $quiz->setName('Quiz '.$j.' '.$office->getName());
                $quiz->setOffice($office);
                $manager->persist($quiz);

                $categories = array();
                for ($c = 0; $c < 10; $c++) {
                    $category = new Category();
                    $category->setName('Cat. '.$c.' '.$quiz->getName());
                    $category->setQuiz($quiz);
                    $categories[] = $category;
                    $manager->persist($category);
                }

                $this->createQuestions($manager, $quiz, $categories);
            }

            $manager->flush();
            $manager->clear();

            $this->createResponse($manager, $office);
        }

        $this->createTouristOfficeUser($manager);
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

        for ($a = 0; $a < mt_rand(1, 5); $a++) {
            $answer = new Answer();
            $answer->setText('Answer '.$a);
            $answer->setDescription('Description');
            $answer->setScore(mt_rand(0, 5));
            $answer->setQuestion($question);
            $manager->persist($answer);

            $newDepth = $depth - rand(1, $depth - 1);
            $this->createQuestions($manager, $quiz, $categories, $newDepth, $answer);
        }
    }

    /**
     * Create response for quizzes
     *
     * @param ObjectManager $manager
     * @param Office $office
     */
    public function createResponse($manager, $office)
    {
        $quizzes = $manager->getRepository('AppBundle:Quiz')->findAllByOffice($office);

        // create some tourists
        for ( $i = 0; $i < mt_rand(2, 5); $i++ ) {
            $tourist = new Tourist();
            $tourist->setReference('Ref. T'.$i);
            $tourist->setCreationDate(new \DateTime());
            $manager->persist($tourist);

            // tourist respond to arbitrary quizzes, not all but at least one
            $randQuizzesKeys = (array)array_rand($quizzes, mt_rand(1, count($quizzes)));

            // loop through quizzes random keys to get single quiz
            for ($j = 0; $j < count($randQuizzesKeys); $j++) {
                $quiz = $quizzes[$randQuizzesKeys[$j]];

                $p = new Participation();
                $p->setCreatedAt(new \DateTime());
                $p->setTourist($tourist);
                $p->setQuiz($quiz);

                $quiz->addParticipation($p);
                $tourist->addParticipation($p);

                $this->respond($manager, $tourist, $quiz->getQuestions()[0]);
            }
        }

        $manager->flush();
    }

    /**
     * Create responses for a question
     *
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

        // repeat...
        $q = $a->getNextQuestion();
        $this->respond($manager, $tourist, $q);
    }

    /**
     * Crate admin users for each offices
     *
     * @param $manager ObjectManager
     */
    public function createTouristOfficeUser($manager)
    {
        $offices = $manager->getRepository('AppBundle:Office')->findAll();
        $userManager = $this->container->get('fos_user.user_manager');

        /**
         * add at least one user per office
         *
         * @var User $user
         * @var Office $office
         */
        foreach ($offices as $key => $office) {
            for ($i = 0; $i < mt_rand(1, 2); $i++ ) {
                $user = $userManager->createUser();

                $user->setUsername('user'.$i.'office'.$key);
                echo "User   user".$i.'office'.$key."   created with password passw0rd\n";
                $user->setPlainPassword("passw0rd");
                $user->setEmail('user'.$i.$key.'@domain.com');
                $user->setEnabled(true);
                $user->setOffice($office);

                $userManager->updateUser($user);
            }
        }
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}