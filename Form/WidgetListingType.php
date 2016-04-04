<?php

namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Bundle\WidgetBundle\Entity\Widget;

/* WidgetListing form type */
class WidgetListingType extends WidgetType
{
    /**
     * define form fields.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['businessEntityId'] !== null) {
            if ($options['namespace'] === null) {
                throw new \Exception('The namespace is mandatory if the business_entity_id is given.');
            }
        }

        if ($options['mode'] === Widget::MODE_STATIC) {
            //if no entity is given, we generate the static form
            $builder->add('items', CollectionType::class, [
                'entry_type'         => WidgetListingItemType::class,
                'allow_add'          => true,
                'allow_delete'       => true,
                'vic_widget_add_btn' => null,
                'by_reference'       => false,
                'options'            => [
                    'namespace'        => $options['namespace'],
                    'businessEntityId' => $options['businessEntityId'],
                    'widget'           => $options['widget'],
                ],
                'attr' => ['id' => 'static'],
            ]);
        }

        if ($options['mode'] === Widget::MODE_ENTITY) {
            $builder
                ->add('slot', HiddenType::class);
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('items', CollectionType::class, [
                        'entry_type'         => WidgetListingItemType::class,
                        'allow_add'          => true,
                        'vic_widget_add_btn' => null,
                        'allow_delete'       => true,
                        'by_reference'       => false,
                        'attr'               => ['id' => $options['businessEntityId']],
                        'options'            => [
                            'namespace'        => $options['namespace'],
                            'businessEntityId' => $options['businessEntityId'],
                            'mode'             => $options['mode'],
                            'widget'           => $options['widget'],
                        ],
                    ]);

            $this->addEntityFields($builder, $options);
        }

        if ($options['mode'] === Widget::MODE_QUERY) {
            $this->addQueryFields($builder, $options);
        }

        if ($options['mode'] === Widget::MODE_BUSINESS_ENTITY) {
            $this->addBusinessEntityFields($builder, $options);
        }

        //add the mode to the form
        $builder->add('mode', HiddenType::class, [
            'data' => $options['mode'],
        ]);

        //add the slot to the form
        $builder->add('slot', HiddenType::class);
    }

    /**
     * Add the fields to the form for the query mode.
     *
     * @param FormInterface|FormBuilderInterface $form
     * @param array                              $options
     */
    protected function addQueryFields($form, $options)
    {
        $form->add('targetPattern');
        $form->add('randomResults', null, [
            'attr' => [
                'data-refreshOnChange' => 'true',
            ],
        ]);
        $form->add('maxResults');

        $form->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                self::disableOrderBy($event->getForm(), $event->getData()->isRandomResults());
            }
        );

        $form->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $randomResults = isset($data['randomResults']) ? $data['randomResults'] : false;
                self::disableOrderBy($event->getForm(), $randomResults);
            }
        );

        parent::addQueryFields($form, $options);
    }

    /* Disable orderBy field if random checkbox is checked */
    protected function disableOrderBy(FormInterface $form, $randomResults)
    {
        switch ($randomResults) {
            case true:
                $form->remove('orderBy');
                break;
            default:
                $form->add('orderBy', null, [
                    'required' => false,
                    'attr'     => [
                        'placeholder' => '[{"by": "name", "order": "DESC"}, {"by": "address", "order": "ASC"}]',
                    ],
                ]);
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class'         => 'Victoire\Widget\ListingBundle\Entity\WidgetListing',
            'widget'             => 'ListingItem',
            'translation_domain' => 'victoire',
        ]);
    }
}
