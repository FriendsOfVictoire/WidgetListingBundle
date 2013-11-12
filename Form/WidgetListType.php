<?php

namespace Victoire\ListBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\CmsBundle\Form\EntityProxyFormType;
use Victoire\CmsBundle\Form\WidgetType;
use Victoire\ListBundle\Form\WidgetListItemType;


/**
 * WidgetList form type
 */
class WidgetListType extends WidgetType
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
                        'type' => new WidgetListItemType($this->entity_name, $this->namespace, $options['widget']),
                        'allow_add' => true,
                        'by_reference' => false,
                        "attr" =>array('id' => 'static')
                    ))
                //
                ;
        } else {
            //else, WidgetType class will embed a EntityProxyType for given entity

            $builder
                ->add('page', null,
                    array(
                        "label" => "",
                        "attr" =>array("class" => "hide")
                    )
                )
                ->add('slot', 'hidden')

                ->add('fields', 'widget_fields', array(
                    "namespace" => $this->namespace,
                    "widget" => $options['widget']
                ))
                ->add('items', 'collection', array(
                        'type' => new WidgetListItemType($this->entity_name, $this->namespace, $options['widget']),
                        'allow_add' => true,
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
            'data_class' => 'Victoire\ListBundle\Entity\WidgetList',
            'widget' => 'listitem'
        ));
    }


    /**
     * get form name
     */
    public function getName()
    {
        return 'appventus_victoirecmsbundle_widgetlisttype';
    }
}
