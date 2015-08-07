<?php
/**
 * Created by PhpStorm.
 * User: Vince
 * Date: 02.08.2015
 * Time: 11:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Tourist;
use AppBundle\Entity\TouristSearch;
use AppBundle\Form\TouristSearchByReferenceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tourist")
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

    /**
     * Creates a search form
     *
     * @Route("/search", name="tourist_search_by_reference")
     * @Method({"POST"})
     */
    public function searchByReferenceAction(Request $request)
    {
        $tourist = new Tourist();
        $form = $this->createForm(
            new TouristSearchByReferenceType(), $tourist, array(
                'action' => $this->generateUrl('tourist_search_by_reference', array(), false)
            )
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $user = $this->getUser();
            $tourists = $em->getRepository('AppBundle:Tourist')->findByReference($data->getReference(), $user->getOffice());

            return $this->render('tourist/tourist_search_result.html.twig', array(
                'tourists' => $tourists
            ));
        }

        return $this->render('tourist/tourist_search.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}