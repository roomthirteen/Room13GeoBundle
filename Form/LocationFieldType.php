<?php

namespace Room13\GeoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManager;

class LocationFieldType extends AbstractType
{
    function __construct(EntityManager $em)
    {
    }


    function getName()
    {
        return 'room13_geo_location';
    }


}
