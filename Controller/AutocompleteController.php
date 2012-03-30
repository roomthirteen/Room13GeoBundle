<?php

namespace Room13\GeoBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Room13\GeoBundle\Entity\Location;


class AutocompleteController extends Controller
{

    /**
     * @var \Doctrine\ORM\EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

     /**
      *  @Route("/autocomplete/{type}")
      */
    public function locationAction($type)
    {

        $term = strtolower(trim($this->getRequest()->get('term',null)));
        $type = \Room13\GeoBundle\Entity\Location::mapStringToClass($type);

        if($term === null || strlen($term) === 0)
        {
            throw new \Gedmo\Exception\InvalidArgumentException('No search term specified');
        }

        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('l')
            ->from($type,'l')
            ->where($qb->expr()->like('l.slug','?2'))
            ->setMaxResults(10)
            ->setParameter(2,$term.'%')
        ;

        $result = array();

        foreach($qb->getQuery()->execute() as $location)
        {
            $result[]= array(
                'id'=>$location->getId(),
                'label'=>$location->__toString()
            );
        }

        $res = new Response(json_encode($result));
        $res->headers->set('Content-Type','application/json');

        return $res;

    }
}
