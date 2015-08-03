<?php

namespace AppBundle\Controller;

use AppBundle\Response\GraphQuizResponse;
use AppBundle\Response\TreeQuizResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}
