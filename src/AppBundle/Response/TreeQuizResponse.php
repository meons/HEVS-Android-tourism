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
        $info = sprintf('<span class="label label-primary">%s</span>', $q->getCategory()->getName());
        $nodeQ = array(
            'text' => sprintf('%s %s', $info, $q->getText()),
            'children' => array(),
            'state' => array(
                'opened' => true,
            ),
            'id' => 'q-'.$q->getId(),
            'icon' => 'glyphicon glyphicon-question-sign',
            'type' => 'question',
        );
        $tree[] = &$nodeQ;

        // Add answers
        $answers = $q->getAnswers();
        foreach ($answers as $a) {
            $info = sprintf('<span class="label label-default">%s</span>', $a->getScore() > 0 ? '+'.$a->getScore() : $a->getScore());
            $nodeA = array(
                'text' => sprintf('%s %s', $info, $a->getText()),
                'children' => array(),
                'state' => array(
                    'opened' => true,
                ),
                'id' => 'a-'.$a->getId(),
                'icon' => 'glyphicon glyphicon-arrow-right',
                'type' => 'answer',
            );
            $nodeQ['children'][] = &$nodeA;
            $this->tree($nodeA['children'], $a->getNextQuestion());
            unset($nodeA);
        }
    }
}