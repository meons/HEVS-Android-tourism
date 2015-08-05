<?php

namespace AppBundle\Controller;

use AppBundle\Response\GraphQuizResponse;
use AppBundle\Response\TreeQuizResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Quiz;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $users = $em->getRepository('AppBundle:User')->findAllByOffice($user->getOffice());
        $quizzes = $em->getRepository('AppBundle:Quiz')->findAllByOffice($user->getOffice());

        foreach ($quizzes as $quiz) {
            dump($quiz->getTourists());
            $tourists[] = $quiz->getTourists();
        }

        dump($tourists);

        return $this->render('default/index.html.twig', array(
            'users' => $users,
            'quizzes' => $quizzes,
            'tourists' => $tourists
        ));
    }
}