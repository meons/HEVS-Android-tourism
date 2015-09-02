<?php

namespace AppBundle\Form;

use AppBundle\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecommendationType extends AbstractType
{
    private $quiz;

    /**
     * RecommendationType constructor.
     */
    public function __construct($quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category',
                'property' => 'name',
                'choices' => $this->quiz->getCategories(),
            ))
            ->add('recommendationCriterias', 'collection', array(
                'type' => new RecommendationCriteriaType(),
                'allow_add' => true,
                'by_reference' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Recommendation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_recommendation';
    }
}
