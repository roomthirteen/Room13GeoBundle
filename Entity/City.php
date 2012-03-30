<?php

namespace Room13\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="room13_geo_city")
 */
class City extends Location
{

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="cities")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $country;


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
