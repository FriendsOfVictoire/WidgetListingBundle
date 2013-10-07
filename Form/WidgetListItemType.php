<?php
namespace Victoire\ListBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\CmsBundle\Form\WidgetType;

class WidgetListItemType extends AbstractType
{

    protected $entity;
    protected $class;
    protected $widget;

    public function __construct($entity, $class, $widget)
    {
        $this->class = $class;
        $this->entity = $entity;
        $this->widget = $widget;
    }

    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        //choose form mode
        if ($this->entity === null) {
            //if no entity is given, we generate the static form
            $builder
                ->add('list', null,
                    array(
                        "label" => "",
                        "attr" => array('class' => "hide")
                    )
                )
                ->add('title')
                ->add('description');
        } else {
            //else, WidgetType class will embed a EntityProxyType for given entity
            $builder
                ->add('position')
                ->add('list', null,
                    array(
                        "label" => "",
                        "attr" => array('class' => "hide")
                    )
                )
                ->add('entity', 'entity_proxy', array(
                    "entity" => $this->entity,
                    "class" => $this->class,
                    "widget" => $this->widget
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
            'data_class' => 'Victoire\ListBundle\Entity\WidgetListItem',
            'widget' => null,
        ));
    }


    /**
     * get form name
     */
    public function getName()
    {
        return 'list_items';
    }
}
