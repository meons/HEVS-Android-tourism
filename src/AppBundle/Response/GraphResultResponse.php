<?php

namespace AppBundle\Response;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Symfony\Component\HttpFoundation\JsonResponse;

class GraphResultResponse extends JsonResponse
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
            'name' => sprintf('Question %s ?', $q->getId()),
            'type' => 'question',
            'children' => array(),
        );
        $tree[] = &$nodeQ;

        // Add answers
        $answers = $q->getAnswers();
        foreach ($answers as $a) {
            $nodeA = array(
                'name' => $a->getText(),
                'children' => array(),
                'answered' => $q->getQuiz()->getTourists()->first()->getAnswersResult()->contains($a),
            );
            $nodeQ['children'][] = &$nodeA;
            $this->tree($nodeA['children'], $a->getNextQuestion());
            unset($nodeA);
        }
    }
}