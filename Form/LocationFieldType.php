<?php

namespace Room13\GeoBundle\Form;

use Symfony\Component\Form as Form;

class LocationFieldType extends Form\AbstractType
{


    /**
     * @var Form\DataTransformerInterface
     */
    private $dataTransformer;

    function __construct(Form\DataTransformerInterface $dataTransformer)
    {
      $this->dataTransformer = $dataTransformer;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(Form\FormBuilder $builder, array $options)
    {
        $builder->prependClientTransformer($this->dataTransformer);
    }


    public function getParent(array $options)
    {
        return 'choice';
    }


    function getName()
    {
        return 'room13_geo_location';
    }


}
