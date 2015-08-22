<?php
/**
 * Created by PhpStorm.
 * User: Vince
 * Date: 02.08.2015
 * Time: 11:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Quiz;
use AppBundle\Entity\Result;
use AppBundle\Entity\Tourist;
use AppBundle\Response\GraphResultResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/result")
 */
class ResultController extends Controller
{
    /**
     * List all quizzes belonging to a tourist
     *
     * @Route("/tourist/{id}", name="result_tourist_quizzes")
     */
    public function indexAction(Tourist $tourist)
    {
        $quizzes = $tourist->getQuizzes();

        return $this->render('result/tourist_quizzes.html.twig', array('tourist' => $tourist, 'quizzes' => $quizzes));
    }

    /**
     * @Route("/tourist/{tourist_id}/quiz/{quiz_id}", name="result_tourist_quiz")
     * @ParamConverter("tourist", options={"mapping": {"tourist_id": "id"}})
     * @ParamConverter("quiz", options={"mapping": {"quiz_id": "id"}})
     */
    public function listTouristAction(Tourist $tourist, Quiz $quiz)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Result')->getAllByTouristQuiz($tourist, $quiz);

        /** @var Result $result */
        $scores = array();
        foreach ($results as $result) {
            $category = $result->getAnswer()->getQuestion()->getCategory();
            $score = $result->getAnswer()->getScore();
            if (!isset($scores[$category->getId()])) {
                $scores[$category->getId()] = array(
                    'category' => $category,
                    'total' => 0,
                );
            }
            $scores[$category->getId()]['total'] += $score;
        }

        // find max score
        $maxScore = array_reduce($scores, function($v, $w) {
            return max($v, $w['total']);
        }, -9999999);

        return $this->render('result/tourist_result.html.twig', array(
            'quiz' => $quiz,
            'tourist' => $tourist,
            'results' => $results,
            'scores' => $scores,
            'max_score' => $maxScore,
        ));
    }

    /**
     * This method returns an ajax response with categories and scores.
     *
     * @Route("/tourist/{tourist_id}/quiz/{quiz_id}/data", name="result_show_radar_plot_tourist_data")
     * @ParamConverter("tourist", options={"mapping": {"tourist_id": "id"}})
     * @ParamConverter("quiz", options={"mapping": {"quiz_id": "id"}})
     */
    public function showRadarPlotTouristAction(Tourist $tourist, Quiz $quiz)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Result')->getAllByTouristQuiz($tourist, $quiz);

        /** @var Result $result */
        $scores = array();
        $categories = array();
        foreach ($results as $key => $result) {

            $category = $result->getAnswer()->getQuestion()->getCategory();
            $score = $result->getAnswer()->getScore();

            if (!isset($scores[$category->getId()]) || !isset($categories[$category->getId()])) {
                $scores[$category->getId()] = 0;
                $categories[$category->getId()] = $category->getName();
            }

            $scores[$category->getId()] += $score;
        }

        // crate a result array and remove array keys
        $result = array(
            'scores' => array_values($scores),
            'categories' => array_values($categories)
        );

        return new JsonResponse($result);
    }

    /**
     * @Route("/tourist/{tourist_id}/quiz/{quiz_id}/graph/data", name="result_tourist_quiz_graph_data")
     * @ParamConverter("quiz", options={
     *      "repository_method" = "findOneByTourist",
     *      "mapping" = {"quiz_id": "quiz", "tourist_id": "tourist"},
     *      "map_method_signature" = true
     * })
     */
    public function showGraphDataAction(Quiz $quiz)
    {
        return new GraphResultResponse($quiz);
    }
}