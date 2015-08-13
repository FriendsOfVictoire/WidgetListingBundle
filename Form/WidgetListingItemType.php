<?php
namespace Victoire\Widget\ListingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Bundle\WidgetBundle\Entity\Widget;

/**
 *
 */
class WidgetListingItemType extends AbstractType
{
    /**
     * define form fields
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
                ->add('entity_proxy', 'entity_proxy', array(
                    'business_entity_id' => $businessEntityId,
                    'namespace'          => $namespace,
                    'widget'             => $options['widget']
                ));

            //add the remove button
            $this->addRemoveButton($builder);
        }

        $builder->add('position', 'hidden', array(
                'data' => 0,
                'attr' => array(
                    'data-type' => 'position'
                )
            )
        );
    }

    /**
     *
     * @param FormBuilderInterface $builder
     */
    protected function addRemoveButton(FormBuilderInterface $builder)
    {
        //add the remove button
        $builder->add('removeButton', 'button', array(
            'label' => 'widget.form.WidgetListingItemType.removeButton.label',
            'attr' => array(
                'data-action' => 'remove-block',
                'class'       => 'vic-btn vic-btn-remove vic-btn-large vic-pull-right'
            )
        ));
    }

    /**
     * get form name
     *
     * @return string The name of the form
     */
    public function getName()
    {
        return 'victoire_widget_form_listingitem';
    }

    /**
     * bind form to WidgetRedactor entity
     * @param OptionsResolverInterface $resolver
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Victoire\Widget\ListingBundle\Entity\WidgetListingItem',
            'translation_domain' => 'victoire'
        ));

        $resolver->setOptional(array('widget'));
        $resolver->setOptional(array('filters'));
        $resolver->setOptional(array('slot'));
        $resolver->setOptional(array('namespace'));
        $resolver->setOptional(array('businessEntityId'));
        $resolver->setOptional(array('mode'));

    }
}
