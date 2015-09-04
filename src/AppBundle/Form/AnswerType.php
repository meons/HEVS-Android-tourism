<?php

namespace AppBundle\Form;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    private $question;

    /**
     * {@inheritdoc}
     */
    function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('description')
            ->add('score')
            ->add('nextQuestion', 'entity', array(
                'class' => 'AppBundle\Entity\Question',
                'property' => 'text',
                'choices' => $this->question->getQuiz()->getQuestions(),
                'required' => false,
                'empty_value' => ' ',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Answer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_answer';
    }
}
