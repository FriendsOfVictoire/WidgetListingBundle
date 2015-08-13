<?php

namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Bundle\WidgetBundle\Entity\Widget;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

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
        $businessEntityId = $options['businessEntityId'];
        $mode = $options['mode'];

        if ($businessEntityId !== null) {
            if ($namespace === null) {
                throw new \Exception('The namespace is mandatory if the business_entity_id is given.');
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
                    'businessEntityId' => $businessEntityId,
                    'widget'    => $options['widget']
                ),
                "attr" => array('id' => 'static')
            ));
        }

        if ($mode === Widget::MODE_ENTITY) {
            $builder
                ->add('slot', 'hidden');
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('items', 'collection', array(
                        'type'               => 'victoire_widget_form_listingitem',
                        'allow_add'          => true,
                        'vic_widget_add_btn' => null,
                        'allow_delete'       => true,
                        'by_reference'       => false,
                        "attr"               => array('id' => $businessEntityId),
                        'options'            => array(
                            'namespace'        => $namespace,
                            'businessEntityId' => $businessEntityId,
                            'mode'             => $mode,
                            'widget'           => $options['widget']
                        ),
                    ));

            $this->addEntityFields($builder);
        }

        if ($mode === Widget::MODE_QUERY) {
            $this->addQueryFields($builder);
        }

        if ($mode === Widget::MODE_BUSINESS_ENTITY) {
            $this->addBusinessEntityFields($builder);
        }

        //add the mode to the form
        $builder->add('mode', 'hidden', array(
            'data' => $mode
        ));

        //add the slot to the form
        $builder->add('slot', 'hidden', array());

    }

    /**
     * Add the fields to the form for the query mode
     *
     * @param unknown $form
     */
    protected function addQueryFields($form)
    {

        $form->add('targetPattern');
        $form->add('randomResults', null, array(
            'attr' => array(
                'data-refreshOnChange' => "true",
            )
        ));
        $form->add('maxResults');

        $form->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                self::disableOrderBy($event->getForm(), $event->getData()->isRandomResults());
            }
        );

        $form->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) {
                $data = $event->getData();
                $randomResults = isset($data['randomResults']) ? $data['randomResults'] : false;
                self::disableOrderBy($event->getForm(), $randomResults);
            }
        );

        parent::addQueryFields($form);

    }

    /*
     * Disable orderBy field if random checkbox is checked
     */
    protected function disableOrderBy(FormInterface $form, $randomResults)
    {
        switch ($randomResults) {
            case true:
                $form->remove('orderBy');
                break;
            default:
                $form->add('orderBy', null, array(
                    'required' => false,
                    'attr' => array(
                        'placeholder' => '[{"by": "name", "order": "DESC"}, {"by": "address", "order": "ASC"}]'
                    ),
                ));
                break;
        }
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
