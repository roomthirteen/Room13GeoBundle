<?php

namespace Room13\GeoBundle\Twig;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;

class GeoExtension extends \Twig_Extension
{
    /**
     * @var Container
     */
    private $container;



    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function flag($code,$type='png')
    {
        if($code instanceof \Room13\GeoBundle\Entity\Country)
        {
            $code = $code->getCountryCode();
        }
        elseif($code instanceof \Room13\GeoBundle\Entity\Language)
        {
            $code = $code->getLanguageCode();
        }

        $path = 'bundles/room13geo/flags/'.$type.'/'.$code.'.'.$type;
        $url = $this->container->get('templating.helper.assets')->getUrl($path);

        return sprintf(
            '<img src="%s" alt="%s" />',
            $url,
            $code
        );
    }

    public function getFilters()
    {
        return array(
            'room13_geo_flag' => new \Twig_Filter_Method($this,'flag',array('is_safe'=>array('html'))),
        );
    }


    public function getName()
    {
        return 'room13_geo';
    }
}