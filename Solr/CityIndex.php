<?php

namespace Room13\GeoBundle\Solr;


use Room13\SolrBundle\Solr\Index\DoctrineSolrIndex;

use Doctrine\ORM\EntityManager;

class CityIndex extends DoctrineSolrIndex
{

    public function getName()
    {
        return 'room13_geo_city';
    }


    public function getFields()
    {
        return array(
            'name'  => 's',
            'lat'   => 'f',
            'lng'   => 'f',
        );
    }

    public function getType()
    {
        return 'Room13\GeoBundle\Entity\City';
    }


}
