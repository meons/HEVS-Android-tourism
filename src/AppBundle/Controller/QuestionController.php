<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Quiz;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Question controller.
 *
 * @Route("/question")
 */
class QuestionController extends Controller
{
    /**
     * Lists all Question entities.
     *
     * @Route("/", name="question_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $questions = $em->getRepository('AppBundle:Question')->findAll();

        return $this->render('question/index.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * Creates a new Question entity.
     *
     * @Route("/new", name="question_new", options={"expose":true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if (!$request->query->has('previousAnswer')) {
            throw $this->createAccessDeniedException('previousAnswer parameter must be defined');
        }

        $em = $this->getDoctrine()->getManager();

        $question = new Question();
        $question->addAnswer(new Answer());
        $previousAnswer = $em->getRepository('AppBundle:Answer')->find($request->query->get('previousAnswer'));
        $question->addPreviousAnswer($previousAnswer);
        $question->setQuiz($previousAnswer->getQuestion()->getQuiz());

        $form = $this->createForm(new QuestionType(), $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'changes_saved');

            return $this->redirectToRoute('quiz_edit', array(
                'id' => $question->getQuiz()->getId(),
                'tab_enabled' => 'tab-structure',
            ));
        }

        return $this->render('question/new.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

    /**
     * Finds and displays a Question entity.
     *
     * @Route("/{id}", name="question_show")
     * @Method("GET")
     */
    public function showAction(Question $question)
    {
        $deleteForm = $this->createDeleteForm($question);

        return $this->render('question/show.html.twig', array(
            'question' => $question,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{id}/edit", name="question_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Question $question)
    {
        $originalAnswers = new ArrayCollection();
        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $deleteForm = $this->createDeleteForm($question);
        $editForm = $this->createForm(new QuestionType(), $question);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Remove answers
            foreach ($originalAnswers as $answer) {
                if (!$question->getAnswers()->contains($answer)) {
                    $em->remove($answer);
                }
            }

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'changes_saved');

            return $this->redirectToRoute('quiz_edit', array(
                'id' => $question->getQuiz()->getId(),
                'tab_enabled' => 'tab-structure',
            ));
        }

        return $this->render('question/edit.html.twig', array(
            'question' => $question,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Question entity.
     *
     * @Route("/{id}", name="question_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Question $question)
    {
        $form = $this->createDeleteForm($question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($question);
            $em->flush();
        }

        $this->addFlash('success', 'changes_saved');

        return $this->redirectToRoute('quiz_edit', array(
            'id' => $question->getQuiz()->getId(),
            'tab_enabled' => 'tab-structure',
        ));
    }

    /**
     * Creates a form to delete a Question entity.
     *
     * @param Question $question The Question entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Question $question)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('question_delete', array('id' => $question->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
