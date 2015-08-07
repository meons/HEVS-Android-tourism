<?php
/**
 * Created by PhpStorm.
 * User: Vince
 * Date: 06.08.2015
 * Time: 20:33
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver;

class TouristSearchByReferenceType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('creationDate');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'appbundle_tourist_search';
    }

    /**
     * @return TouristType
     */
    public function getParent()
    {
        return new TouristType();
    }
}