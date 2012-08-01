<?php

namespace Room13\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class City extends Location
{

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="cities")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     *
     */
    private $country;


    /**
     * @ORM\OneToMany(targetEntity="Spot", mappedBy="city")
     *
     * @Serializer\Exclude()
     *
     */
    private $spots;


    public function __construct()
    {
        $this->spots = new ArrayCollection();
    }

    function __toString()
    {
        return $this->getName().' ('.$this->country->getName().')';
    }

    public function getType()
    {
        return 'city';
    }


    /**
     * @param \Room13\GeoBundle\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \Room13\GeoBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
