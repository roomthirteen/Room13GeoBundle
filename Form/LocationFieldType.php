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

    public function buildView(Form\FormView $view, Form\FormInterface $form)
    {
        parent::buildView($view, $form);

        if($form->getData()!==null)
        {
            $view->set('location',$form->getData());
            $view->set('has_location',true);
        }
        else
        {
            $view->set('has_location',false);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(Form\FormBuilder $builder, array $options)
    {
        $builder
            ->prependClientTransformer($this->dataTransformer)
            ->add('id','hidden')
            ->add('name','text')
        ;
    }

    public function getDefaultOptions()
    {
        return array(
            'widget_class'      => 'Room13LocationType',
            'error_bubbling'    => false,
        );
    }


    public function getParent(array $options)
    {
        return 'field';
    }


    function getName()
    {
        return 'room13_geo_location';
    }


}
