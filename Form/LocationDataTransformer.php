<?php

namespace Room13\GeoBundle\Form;

use Doctrine\ORM\EntityManager;
use Room13\GeoBundle\Entity\Location;

class LocationDataTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    function transform($value)
    {
        $transformedValue = array(
            'id'=>'',
            'name'=>''
        );

        if($value instanceof Location)
        {
            $transformedValue['id'] = $value->getId();
            $transformedValue['name'] = $value->__toString();
        }

        return $transformedValue;
    }


    function reverseTransform($value)
    {
        if(is_array($value) && isset($value['id']))
        {
            $repository = $this->em->getRepository('Room13GeoBundle:City');
            return $repository->findOneById($value);
        }

        return null;
    }
}
