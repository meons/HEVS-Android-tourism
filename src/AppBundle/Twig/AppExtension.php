<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Tourist;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tourist_gender', array($this, 'getTouristGender')),
        );
    }

    public function getTouristGender(Tourist $tourist)
    {
        if ($tourist->getGender() === Tourist::GENDER_MALE) {
            return 'tourist.gender.male';
        } else {
            return 'tourist.gender.female';
        }
    }

    public function getName()
    {
        return 'app.my_app_extension';
    }
}