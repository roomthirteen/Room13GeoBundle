<?php

namespace Room13\GeoBundle\Solr;


use Room13\SolrBundle\Solr\Index\DoctrineSolrIndex;

use Doctrine\ORM\EntityManager;

class LocationIndex extends DoctrineSolrIndex
{

    public function getName()
    {
        return 'room13_geo_location';
    }

    public function indexObject($object)
    {
        $document = parent::indexObject($object);

        $transRepo = $this->getEntityManager()->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $transRepo->findTranslations($object);

        // index name and translations as lowercase fields
        $document->addField('name_t',$object->getName());
        foreach($translations as $locale=>$fields)
        {
            if(isset($fields['name']))
            {
                $document->addField('name_t',$fields['name']);
            }
        }

        return $document;
    }


    public function getFields()
    {
        return array(
            //'name'  => 's', -> will be added in indexObject method
            'type'  => 's',
            'lat'   => 'f',
            'lng'   => 'f',
        );
    }

    public function getType()
    {
        return 'Room13\GeoBundle\Entity\Location';
    }


}
