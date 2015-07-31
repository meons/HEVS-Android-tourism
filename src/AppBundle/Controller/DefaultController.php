<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/quiz/{id}", name="quiz")
     */
    public function quizAction($id)
    {
        return $this->render('default/quiz.html.twig', array('id' => $id));
    }

    /**
     * @Route("/quiz/{id}/data", name="quiz_data")
     */
    public function quizDataAction($id)
    {
        $quiz = $this->getDoctrine()->getRepository('AppBundle:Quiz')->find($id);
        $tree = array();
        $this->tree($tree, $quiz->getQuestions()[0]);
        return new JsonResponse($tree[0]);
    }

    /**
     * @param $tree array
     * @param $q Question
     */
    protected function tree(&$tree, $q)
    {
        if ($q === null) {
            return;
        }

        // Add question
        $nodeQ = array(
            'name' => sprintf('Question %s ?', $q->getId()),
            'children' => array(),
        );
        $tree[] = &$nodeQ;

        // Add answers
        $answers = $q->getAnswers();
        foreach ($answers as $a) {
            $nodeA = array(
                'name' => $a->getText(),
                'children' => array(),
            );
            $nodeQ['children'][] = &$nodeA;
            $this->tree($nodeA['children'], $a->getNextQuestion());
            unset($nodeA);
        }
    }
}
