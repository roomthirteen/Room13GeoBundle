<?php

namespace Room13\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


class NullLocation extends Location
{

    public function __construct()
    {
    }

    public function getType()
    {
        return 'null';
    }


}
