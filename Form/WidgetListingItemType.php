<?php

namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Victoire\Bundle\CoreBundle\Form\EntityProxyFormType;

class WidgetListingItemType extends AbstractType
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
        $namespace = $options['namespace'];
        $businessEntityId = $options['businessEntityId'];

        if ($businessEntityId !== null) {
            if ($namespace === null) {
                throw new \Exception('The namespace is mandatory if the business_entity_id is given.');
            }
        }

        //choose form businessEntityId
        if ($businessEntityId === null) {
            //if no entity is given, we generate the static form that contains only title and description
            $builder
                ->add('title');

            //add the remove button
            $this->addRemoveButton($builder);
        } else {

            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('entity_proxy', EntityProxyFormType::class, [
                    'business_entity_id' => $businessEntityId,
                    'namespace'          => $namespace,
                    'widget'             => $options['widget'],
                ]);

            //add the remove button
            $this->addRemoveButton($builder);
        }

        $builder->add('position', HiddenType::class, [
                'data' => 0,
                'attr' => [
                    'data-type' => 'position',
                ],
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     */
    protected function addRemoveButton(FormBuilderInterface $builder)
    {
        //add the remove button
        $builder->add('removeButton', ButtonType::class, [
            'label' => 'widget.form.WidgetListingItemType.removeButton.label',
            'attr'  => [
                'data-action' => 'remove-block',
                'class'       => 'vic-btn vic-btn-remove vic-btn-large vic-pull-right',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Victoire\Widget\ListingBundle\Entity\WidgetListingItem',
            'translation_domain' => 'victoire',
        ]);

        $resolver->setDefined([
            'widget',
            'filters',
            'slot',
            'namespace',
            'businessEntityId',
            'mode',
        ]);
    }
}
