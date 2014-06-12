<?php

namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\EntityProxyFormType;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Widget\ListingBundle\Form\WidgetListingItemType;


/**
 *
 * WidgetListing form type
 */
class WidgetListingType extends WidgetType
{

    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $namespace = $options['namespace'];
        $entityName = $options['entityName'];

        if ($entityName !== null) {
            if ($namespace === null) {
                throw new \Exception('The namespace is mandatory if the entity_name is given.');
            }
        }

        //choose form mode
        if ($entityName === null) {
            //if no entity is given, we generate the static form
            $builder->add('items', 'collection', array(
                'type' => 'victoire_widget_form_listingitem',
                'allow_add' => true,
                'widget_add_btn' => null,
                'by_reference' => false,
                'options' => array(
                    'namespace' => $namespace,
                    'entityName' => $entityName
                ),
                "attr" => array('id' => 'static')
            ));
        } else {
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('slot', 'hidden')

                ->add('fields', 'widget_fields', array(
                    "namespace" => $namespace,
                    "widget" => $options['widget']
                ))
                ->add('items', 'collection', array(
                        'type' => 'victoire_widget_form_listingitem',
                        'allow_add' => true,
                        'widget_add_btn' => null,
                        'by_reference' => false,
                        'options' => array(
                            'namespace' => $namespace,
                            'entityName' => $entityName
                        ),
                        "attr" => array('id' => $entityName)
                    ));
        }
    }

    /**
     * bind form to WidgetRedactor entity
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        zdebug(__FUNCTION__);

        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class'         => 'Victoire\Widget\ListingBundle\Entity\WidgetListing',
            'widget'             => 'listingitem',
            'translation_domain' => 'victoire'
        ));
    }


    /**
     * get form name
     *
     * @return string The name of the form
     */
    public function getName()
    {
        return 'victoire_widget_form_listing';
    }
}
