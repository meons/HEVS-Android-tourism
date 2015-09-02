<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Category;
use AppBundle\Entity\Question;
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
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        // Quiz
        $quiz = new Quiz();
        $quiz->setName('...');

        // Office
        $quiz->setOffice($this->getUser()->getOffice());

        // Category
        $category = (new Category())->setName('...');
        $quiz->addCategory($category);

        // Question/Answer
        $question = (new Question())->setText('...');
        $question->setCategory($category);
        $answer = (new Answer())->setText('...');
        $question->addAnswer($answer);
        $quiz->addQuestion($question);

        $em = $this->getDoctrine()->getManager();
        $em->persist($quiz);
        $em->flush();

        return $this->redirectToRoute('quiz_edit', array('id' => $quiz->getId()));
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
     * @Route("/{id}/tree", name="quiz_show_tree")
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
        $recommendations = $quiz->getRecommendations();

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
            'recommendations' => $recommendations
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
