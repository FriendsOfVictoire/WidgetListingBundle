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
        $entityName = $options['entityName'];

        if ($entityName !== null) {
            if ($namespace === null) {
                throw new \Exception('The namespace is mandatory if the entity_name is given.');
            }
        }

        //choose form entityName
        if ($entityName === null) {
            //if no entity is given, we generate the static form that contains only title and description
            $builder
                ->add('title')
                ->add('description');

            //add the remove button
            $this->addRemoveButton($builder);
        } else {

            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('position', 'hidden', array(
                    'data' => 0
                ))
                ->add('entity_proxy', 'entity_proxy', array(
                    'entity_name' => $entityName,
                    'namespace'   => $namespace,
                    'widget'      => $options['widget']
                ));

            //add the remove button
            $this->addRemoveButton($builder);
        }
    }

    /**
     *
     * @param FormBuilderInterface $builder
     */
    protected function addRemoveButton(FormBuilderInterface $builder)
    {
        //add the remove button
        $builder->add('collection.remove', 'button', array(
            'label' => 'widget.form.collection.remove',
            'attr' => array('data-action' => 'remove-block')
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
        $resolver->setOptional(array('entityName'));
        $resolver->setOptional(array('mode'));

    }
}
