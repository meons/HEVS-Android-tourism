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
 * @Route("/{_locale}/tourist")
 */
class TouristController extends Controller
{
    /**
     * List all tourists belonging to a tourist office
     *
     * @Route("/", name="tourist_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tourists = $em->getRepository('AppBundle:Tourist')->findAllByOffice($user->getOffice());

        return $this->render('tourist/tourist_list.html.twig', array('tourists' => $tourists));
    }
}