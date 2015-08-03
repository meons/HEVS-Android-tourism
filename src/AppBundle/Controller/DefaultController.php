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
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/quiz-graph/{id}", name="quiz_graph")
     */
    public function quizGraphAction($id)
    {
        return $this->render('default/quiz_graph.html.twig', array('id' => $id));
    }

    /**
     * @Route("/quiz-graph/{id}/data", name="quiz_graph_data")
     */
    public function quizGraphDataAction($id)
    {
        $quiz = $this->getDoctrine()->getRepository('AppBundle:Quiz')->find($id);

        return new GraphQuizResponse($quiz);
    }

    /**
     * @Route("/quiz-tree/{id}", name="quiz_tree")
     */
    public function quizTreeAction($id)
    {
        return $this->render('default/quiz_tree.html.twig', array('id' => $id));
    }

    /**
     * @Route("/quiz-tree/{id}/data", name="quiz_tree_data")
     */
    public function quizTreeDataAction($id)
    {
        $quiz = $this->getDoctrine()->getRepository('AppBundle:Quiz')->find($id);

        return new TreeQuizResponse($quiz);
    }
}
