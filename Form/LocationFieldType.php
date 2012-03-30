<?php

namespace Room13\GeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManager;

class LocationFieldType extends AbstractType
{
    function __construct(EntityManager $em)
    {
    }

    public function getAllowedOptionValues(array $options)
    {
        return parent::getAllowedOptionValues($options);
    }

    public function getParent(array $options)
    {
        return 'integer';
    }


    public function getDefaultOptions(array $options)
    {
        return array(
            'type' => 'country',
        );
    }

    function getName()
    {
        return 'room13_geo_location';
    }


}
