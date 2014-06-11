<?php

namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\EntityProxyFormType;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Widget\ListingBundle\Form\WidgetListingItemType;


/**
 * WidgetListing form type
 */
class WidgetListingType extends WidgetType
{

    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //choose form mode
        if ($this->entity_name === null) {
            //if no entity is given, we generate the static form
            $builder

                ->add('items', 'collection', array(
                        'type' => new WidgetListingItemType($this->entity_name, $this->namespace, $options['widget']),
                        'allow_add' => true,
                        'widget_add_btn' => null,
                        'by_reference' => false,
                        "attr" =>array('id' => 'static')
                    ))
                //
                ;
        } else {
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('slot', 'hidden')

                ->add('fields', 'widget_fields', array(
                    "namespace" => $this->namespace,
                    "widget" => $options['widget']
                ))
                ->add('items', 'collection', array(
                        'type' => new WidgetListingItemType($this->entity_name, $this->namespace, $options['widget']),
                        'allow_add' => true,
                        'widget_add_btn' => null,
                        'by_reference' => false,
                        "attr" =>array('id' => $this->entity_name)
                    ))
                //
                ;
        }
    }

    /**
     * bind form to WidgetRedactor entity
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Victoire\Widget\ListingBundle\Entity\WidgetListing',
            'widget'             => 'listingitem',
            'translation_domain' => 'victoire'
        ));
    }


    /**
     * get form name
     */
    public function getName()
    {
        return 'appventus_victoirecorebundle_widgetlistingtype';
    }
}
