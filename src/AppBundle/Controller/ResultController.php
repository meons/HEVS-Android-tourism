<?php
/**
 * Created by PhpStorm.
 * User: Vince
 * Date: 02.08.2015
 * Time: 11:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/result")
 */
class ResultController extends Controller
{
    /**
     * @Route("/tourist/{id}", name="result_user")
     */
    public function listAction($id)
    {
        $tourist = $this->getDoctrine()->getRepository('AppBundle:Tourist')->find($id);
        $results = $tourist->getResults();

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

        return $this->render('result/tourist_result.html.twig', array(
            'results' => $results,
            'scores' => $scores,
        ));
    }
}