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
        self::tree($tree, $quiz->getQuestions()[0]);
        parent::__construct($tree[0], $status, $headers);
    }

    /**
     * @param $tree array
     * @param $q Question
     */
    static public function tree(&$tree, $q, $depth = 1)
    {
        if ($q === null || $depth === 0) {
            return;
        }

        // Add question
        $info = sprintf('<span class="label label-primary">%s</span>', $q->getCategory()->getName());
        $nodeQ = array(
            'text' => sprintf('%s %s', $info, $q->getText()),
            'children' => array(),
            'state' => array(
                'opened' => false,
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
                'children' => $depth === 1 ? true : array(),
                'state' => array(
                    'opened' => false,
                ),
                'id' => 'a-'.$a->getId(),
                'icon' => 'glyphicon glyphicon-arrow-right',
                'type' => 'answer',
                'nextQuestion' => $a->getNextQuestion() ? $a->getNextQuestion()->getId() : 0,
            );
            $nodeQ['children'][] = &$nodeA;
            self::tree($nodeA['children'], $a->getNextQuestion(), $depth - 1);
            unset($nodeA);
        }
    }
}