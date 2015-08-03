<?php

namespace AppBundle\Controller;

use AppBundle\Response\GraphQuizResponse;
use AppBundle\Response\TreeQuizResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Quiz;
use AppBundle\Form\QuizType;

/**
 * Quiz controller.
 *
 * @Route("/quiz")
 */
class QuizController extends Controller
{
    /**
     * Lists all Quiz entities.
     *
     * @Route("/", name="quiz_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $quizzes = $em->getRepository('AppBundle:Quiz')->findAllByOffice($user->getOffice());

        return $this->render('quiz/index.html.twig', array(
            'quizzes' => $quizzes,
        ));
    }

    /**
     * Creates a new Quiz entity.
     *
     * @Route("/new", name="quiz_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $quiz = new Quiz();
        $form = $this->createForm(new QuizType(), $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();

            return $this->redirectToRoute('quiz_show', array('id' => $quiz->getId()));
        }

        return $this->render('quiz/new.html.twig', array(
            'quiz' => $quiz,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Quiz entity.
     *
     * @Route("/{id}", name="quiz_show")
     * @Method("GET")
     */
    public function showAction(Quiz $quiz)
    {
        $deleteForm = $this->createDeleteForm($quiz);

        return $this->render('quiz/show.html.twig', array(
            'quiz' => $quiz,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/graph", name="quiz_show_graph")
     */
    public function showGraphAction($id)
    {
        return $this->render('quiz/show_graph.html.twig', array('id' => $id));
    }

    /**
     * @Route("/{id}/graph/data", name="quiz_show_graph_data")
     */
    public function showGraphDataAction($id)
    {
        $quiz = $this->getDoctrine()->getRepository('AppBundle:Quiz')->find($id);

        return new GraphQuizResponse($quiz);
    }

    /**
     * @Route("/{id}/tree", name="quiz_show_tree")
     */
    public function showTreeAction($id)
    {
        return $this->render('quiz/show_tree.html.twig', array('id' => $id));
    }

    /**
     * @Route("/{id}/tree/data", name="quiz_show_tree_data")
     */
    public function showTreeDataAction($id)
    {
        $quiz = $this->getDoctrine()->getRepository('AppBundle:Quiz')->find($id);

        return new TreeQuizResponse($quiz);
    }

    /**
     * Displays a form to edit an existing Quiz entity.
     *
     * @Route("/{id}/edit", name="quiz_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Quiz $quiz)
    {
        $deleteForm = $this->createDeleteForm($quiz);
        $editForm = $this->createForm(new QuizType(), $quiz);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();

            return $this->redirectToRoute('quiz_edit', array('id' => $quiz->getId()));
        }

        return $this->render('quiz/edit.html.twig', array(
            'quiz' => $quiz,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Quiz entity.
     *
     * @Route("/{id}", name="quiz_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Quiz $quiz)
    {
        $form = $this->createDeleteForm($quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quiz);
            $em->flush();
        }

        return $this->redirectToRoute('quiz_index');
    }

    /**
     * Creates a form to delete a Quiz entity.
     *
     * @param Quiz $quiz The Quiz entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Quiz $quiz)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quiz_delete', array('id' => $quiz->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
