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
use AppBundle\Entity\Participation;
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
     * @Route("/{tourist_id}", name="result_tourist_quizzes")
     * @ParamConverter("tourist", options={"mapping": {"tourist_id": "id"}})
     */
    public function indexAction(Tourist $tourist)
    {
        $participation = $tourist->getParticipations();
        //$quizzes = $tourist->getQuizzes();

        //return $this->render('result/tourist_quizzes.html.twig', array('tourist' => $tourist, 'quizzes' => $quizzes));
        return $this->render('result/tourist_quizzes.html.twig', array('tourist' => $tourist, 'partitipation' => $participation));
    }

    /**
     * @Route("/participation/{participation_id}", name="result_tourist_quiz")
     * @ParamConverter("participation", options={"mapping": {"participation_id": "id"}})
     */
    public function listTouristAction(Participation $participation)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Result')->getAllByParticipation($participation);

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

        // Get recommendations
        $recommendations = array();
        foreach ($participation->getQuiz()->getRecommendations() as $recommendation) {
            foreach ($scores as $score) {
                if ($recommendation->getCategory()->getName() === $score['category']->getName()) {
                    $recommendationCriterias = $recommendation->getRecommendationCriterias();
                    foreach ($recommendationCriterias as $criteria) {
                        if ($score['total'] >= $criteria->getThresholdMin() && $score['total'] <= $criteria->getThresholdMax()) {
                            $recommendations[] = array(
                                'cat' => $score['category'],
                                'msg' => $criteria->getMessage(),
                            );
                            break;
                        }
                    }
                }
            }
        }

        //dump($scores);
        // find max score
        $maxScore = array_reduce($scores, function($v, $w) {
            return max($v, $w['total']);
        }, -9999999);

        return $this->render('result/tourist_result.html.twig', array(
            'participation' => $participation,
            'results' => $results,
            'scores' => $scores,
            'max_score' => $maxScore,
            'recommendations' => $recommendations
        ));
    }

    /**
     * This method returns an ajax response with categories and scores.
     *
     * @Route("/participation/{participation_id}/data", name="result_show_radar_plot_tourist_data")
     * @ParamConverter("participation", options={"mapping": {"participation_id": "id"}})
     */
    public function showRadarPlotTouristAction(Participation $participation)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('AppBundle:Result')->getAllByParticipation($participation);

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
     * @Route("/participation/{participation_id}/graph/data", name="result_tourist_quiz_graph_data")
     * @ParamConverter("quiz", options={
     *      "repository_method" = "findOneByParticipation",
     *      "mapping" = {"participation_id": "participation"},
     *      "map_method_signature" = true
     * })
     */
    public function showGraphDataAction(Quiz $quiz)
    {
        return new GraphResultResponse($quiz);
    }
}