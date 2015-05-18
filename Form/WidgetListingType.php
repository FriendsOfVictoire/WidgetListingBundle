<?php

namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Bundle\WidgetBundle\Entity\Widget;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 *
 * WidgetListing form type
 */
class WidgetListingType extends WidgetType
{
    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;

        $namespace = $options['namespace'];
        $entityName = $options['entityName'];
        $mode = $options['mode'];

        if ($entityName !== null) {
            if ($namespace === null) {
                throw new \Exception('The namespace is mandatory if the entity_name is given.');
            }
        }

        if ($mode === Widget::MODE_STATIC) {
            //if no entity is given, we generate the static form
            $builder->add('items', 'collection', array(
                'type' => 'victoire_widget_form_listingitem',
                'allow_add' => true,
                'allow_delete' => true,
                'vic_widget_add_btn' => null,
                'by_reference' => false,
                'options' => array(
                    'namespace' => $namespace,
                    'entityName' => $entityName,
                    'widget'    => $options['widget']
                ),
                "attr" => array('id' => 'static')
            ));
        }

        if ($mode === Widget::MODE_ENTITY) {
            $builder
                ->add('slot', 'hidden')
                ->add('fields', 'widget_fields', array(
                    'label' => 'widget.form.entity.fields.label',
                    'namespace' => $options['namespace'],
                    'widget'    => $options['widget']
                ));
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('items', 'collection', array(
                        'type' => 'victoire_widget_form_listingitem',
                        'allow_add' => true,
                        'vic_widget_add_btn' => null,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'options' => array(
                            'namespace' => $namespace,
                            'entityName' => $entityName,
                            'mode' => $mode,
                            'widget' => $options['widget']
                        ),
                        "attr" => array('id' => $entityName)
                    ));
        }

        if ($mode === Widget::MODE_QUERY) {
            $this->addQueryFields($builder);
        }

        //add the mode to the form
        $builder->add('mode', 'hidden', array(
            'data' => $mode
        ));

        //add the slot to the form
        $builder->add('slot', 'hidden', array());

        //we use the PRE_SUBMIT event to set the mode option
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $options = $this->options;

                //we get the raw data for the widget form
                $rawData = $event->getData();

                //get the posted mode
                $mode = $rawData['mode'];

                //get the form to add more fields
                $form = $event->getForm();

                //the controller does not use the mode to construct the form, so we update it automatically
                if ($mode === Widget::MODE_ENTITY) {
                    $this->addEntityFields($form);
                }

                if ($mode === Widget::MODE_QUERY) {
                    $this->addQueryFields($form);
                }
                if ($mode === Widget::MODE_BUSINESS_ENTITY) {
                    $this->addBusinessEntityFields($form);
                }
            }
        );
    }

    /**
     * Add the fields to the form for the query mode
     *
     * @param unknown $form
     */
    protected function addQueryFields($form)
    {
        $options = $this->options;

        $form->add('targetPattern');
        $form->add('query');
        $form->add('fields', 'widget_fields', array(
            'label' => 'widget.form.entity.fields.label',
            'namespace' => $options['namespace'],
            'widget'    => $options['widget']
        ));
    }
    /**
     * bind form to WidgetRedactor entity
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class'         => 'Victoire\Widget\ListingBundle\Entity\WidgetListing',
            'widget'             => 'ListingItem',
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
