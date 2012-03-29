<?php

namespace Balkanride\FrontendBundle\Twig;

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

    public function flag($country)
    {
        return "f";
    }

    public function getFunctions()
    {
        return array(
            'room13_geo_flag' => new \Twig_Function_Method($this,'flag'),
        );
    }


    public function getName()
    {
        return 'room13_geo';
    }
}