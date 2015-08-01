<?php

namespace AppBundle\Response;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Symfony\Component\HttpFoundation\JsonResponse;

class TreeQuizResponse extends JsonResponse
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Quiz $quiz, $status = 200, $headers = array())
    {
        $tree = array();
        $this->tree($tree, $quiz->getQuestions()[0]);
        parent::__construct($tree[0], $status, $headers);
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
            'text' => sprintf('Question %s ?', $q->getId()),
            'children' => array(),
            'state' => array(
                'opened' => true,
            ),
            'id' => 'q-'.$q->getId(),
            'icon' => 'glyphicon glyphicon-question-sign',
        );
        $tree[] = &$nodeQ;

        // Add answers
        $answers = $q->getAnswers();
        foreach ($answers as $a) {
            $nodeA = array(
                'text' => $a->getText(),
                'children' => array(),
                'state' => array(
                    'opened' => true,
                ),
                'id' => 'a-'.$a->getId(),
                'icon' => 'glyphicon glyphicon-comment',
            );
            $nodeQ['children'][] = &$nodeA;
            $this->tree($nodeA['children'], $a->getNextQuestion());
            unset($nodeA);
        }
    }
}