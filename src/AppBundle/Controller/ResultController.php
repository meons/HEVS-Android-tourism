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

        return $this->render('result/tourist_result.html.twig', array('results' => $results));
    }
}