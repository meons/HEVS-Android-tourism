<?php
/**
 * Created by PhpStorm.
 * User: Vince
 * Date: 02.08.2015
 * Time: 11:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Result;
use AppBundle\Entity\Tourist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/result")
 */
class ResultController extends Controller
{
    /**
     * @Route("/tourists", name="list_tourists")
     */
    public function listAction()
    {
        $tourists = $this->getDoctrine()->getRepository('AppBundle:Tourist')->findAll();

        return $this->render('result/tourist_list.html.twig', array('tourists' => $tourists));
    }

    /**
     * @Route("/tourist/{id}", name="result_user")
     */
    public function listTouristAction(Tourist $tourist)
    {
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