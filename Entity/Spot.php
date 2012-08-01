<?php

namespace Room13\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * Room13\GeoBundle\Entity\Spot
 *
 * @ORM\Entity
 */
class Spot extends Location
{
    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="City", inversedBy="spots")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $city;

    public function getType()
    {
        return 'spot';
    }
}