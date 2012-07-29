<?php

namespace Room13\GeoBundle\Form;

use Symfony\Component\Form as Form;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    public function buildView(Form\FormView $view, Form\FormInterface $form, array $options)
    {

        parent::buildView($view, $form, $options);

        if($form->getData()!==null)
        {
            $view->location = $form->getData();
            $view->has_location = true;
        }
        else
        {
            $view->has_location = false;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->prependClientTransformer($this->dataTransformer)
            ->add('id','hidden')
            ->add('name','text')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget_class'      => 'Room13LocationType',
            'error_bubbling'    => false,
        ));
    }

    public function getParent()
    {
        return 'field';
    }


    function getName()
    {
        return 'room13_geo_location';
    }


}
