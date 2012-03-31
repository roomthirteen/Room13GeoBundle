<?php

namespace Room13\GeoBundle\Form;

use Doctrine\ORM\EntityManager;

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
        if($value instanceof \Room13\GeoBundle\Entity\City)
        {
            return $value->__toString();
        }

        return ''.$value;
    }


    function reverseTransform($value)
    {
        $repository = $this->em->getRepository('Room13GeoBundle:City');
        $entity = $repository->findOneById($value);

        return $entity;
    }
}
