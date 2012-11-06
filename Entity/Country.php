<?php

namespace Room13\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class Country extends Location
{
    /**
     * @var string
     * @ORM\Column(name="country_code",type="string",length=2,unique=true,nullable=false)
     */
    private $countryCode;


    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="country")
     *
     * @Serializer\Exclude()
     *
     */
    private $cities;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Language", inversedBy="countries")
     * @ORM\JoinTable(
     *      name="room13_geo_country_languages",
     *      joinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="language_id", referencedColumnName="id")}
     * )
     *
     * @Serializer\Exclude()
     *
     */
    private $languages;


    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }

    public function getType()
    {
        return 'country';
    }

    public function setCities($cities)
    {
        $this->cities = $cities;
    }

    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}
