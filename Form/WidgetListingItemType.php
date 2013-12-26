<?php
namespace Victoire\ListingBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\CmsBundle\Form\WidgetType;

class WidgetListingItemType extends AbstractType
{

    protected $entity_name;
    protected $namespace;
    protected $widget;

    public function __construct($entity_name, $namespace, $widget)
    {
        $this->namespace   = $namespace;
        $this->entity_name = $entity_name;
        $this->widget      = $widget;
    }

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
                ->add('listing', null,
                    array(
                        "label" => "",
                        "attr"  => array('class' => "hide")
                    )
                )
                ->add('title')
                ->add('description')
                ;
        } else {
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('position')
                ->add('listing', null,
                    array(
                        "label" => "",
                        "attr"  => array('class' => "hide")
                    )
                )
                ->add('entity', 'entity_proxy', array(
                    "entity_name" => $this->entity_name,
                    "namespace"   => $this->namespace,
                    "widget"      => $this->widget
            ));
        }



    }


    /**
     * bind form to WidgetRedactor entity
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'Victoire\ListingBundle\Entity\WidgetListingItem',
            'widget' => null,
        ));
    }


    /**
     * get form name
     */
    public function getName()
    {
        return 'listing_items';
    }
}
