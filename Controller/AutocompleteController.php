<?php

namespace Room13\GeoBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Room13\GeoBundle\Entity\Location;
use Room13\SolrBundle\Solr\Query;

class AutocompleteController extends Controller
{

    /**
     * @var \Doctrine\ORM\EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     * @var \Room13\SolrBundle\Solr\SolrFacade
     * @DI\Inject("room13.solr")
     */
    private $solr;


    /**
     * @Route("/autocomplete/{type}")
     */
    public function locationAction($type)
    {

        $term = trim($this->getRequest()->get('term', null));

        if($term === null || strlen($term) === 0)
        {
            throw new \InvalidArgumentException('No search term specified');
        }


        $q = new Query(Query::escape($term).'*','name_t');
        $q->andTerm($type,'type_s');

        $result = $this->solr->search($q);

        $data = array();

        foreach($result as $item)
        {
            $data[] = array(
                'id'    => $item->meta_id,
                'label' => $item->meta_name
            );
        }

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;


    }

    /*

   public function locationAction($type)
   {

       $term = strtolower(trim($this->getRequest()->get('term',null)));

       if($term === null || strlen($term) === 0)
       {
           throw new \InvalidArgumentException('No search term specified');
       }

       // TODO: this dependency needs to go away or made explicit
       $term = \Gedmo\Sluggable\Util\Urlizer::transliterate($term);

       $currentLocaleResults = $this->searchBaseLocale($term);
       $otherLocalesResults = $this->searchOtherLocales($term);



       $results = array_merge($currentLocaleResults,$otherLocalesResults);
       $data = array();
       $ids = array();

       foreach($results as $result)
       {
           $data[] = array(
               'id'    =>$result['id'],
               'label' =>$result['name'],
           );
       }

       $res = new Response(json_encode($data));
       $res->headers->set('Content-Type','application/json');

       return $res;


   }
    */

    public function oldAndSuckingFunction($type)
    {
        $term = strtolower(trim($this->getRequest()->get('term', null)));

        // TODO: this dependency needs to go away or made explicit
        $term = \Gedmo\Sluggable\Util\Urlizer::transliterate($term);

        $currentLocaleResults = $this->searchBaseLocale($term);
        $otherLocalesResults = $this->searchOtherLocales($term);


        $results = array_merge($currentLocaleResults, $otherLocalesResults);
        $data = array();
        $ids = array();

        foreach ($results as $result) {
            $data[] = array(
                'id' => $result['id'],
                'label' => $result['name'],
            );
        }

        $res = new Response(json_encode($data));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    private function searchOtherLocales($search)
    {
        $stmt = $this->em->getConnection()->prepare("
          SELECT t_slug.foreign_key AS id, t_name.content AS name
          FROM ext_translations t_slug
          INNER JOIN ext_translations t_name
            ON t_name.foreign_key = t_slug.foreign_key
            AND t_name.object_class = t_slug.object_class
            AND t_name.locale = t_slug.locale
            AND t_name.field = :name_field
          WHERE t_slug.object_class= :class
            AND t_slug.field= :slug_field
            AND t_slug.content LIKE :search
        ");

        $stmt->bindValue('class', 'Room13\GeoBundle\Entity\Location');
        $stmt->bindValue('name_field', 'name');
        $stmt->bindValue('slug_field', 'slug');
        $stmt->bindValue('search', '%' . $search . '%');

        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function searchBaseLocale($search)
    {
        $stmt = $this->em->getConnection()->prepare("
          SELECT id,name
          FROM room13_geo_location l
          WHERE l.slug LIKE :search
        ");

        $stmt->bindValue('search', '%' . $search . '%');

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
