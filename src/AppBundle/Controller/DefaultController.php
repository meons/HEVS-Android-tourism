<?php

namespace AppBundle\Controller;

use AppBundle\Response\GraphQuizResponse;
use AppBundle\Response\TreeQuizResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        $user = $this->getUser();
        $quizzes = $em->getRepository('AppBundle:Quiz')->findAllByOffice($user->getOffice());

        //$tourists = $em->getRepository('AppBundle:Tourist')->findAll();
        $tourists = $em->getRepository('AppBundle:Tourist')->findAllByOffice($user->getOffice());

        return $this->render('default/index.html.twig', array(
            'users' => $users,
            'quizzes' => $quizzes,
            'tourists' => $tourists
        ));
    }
}