<?php
namespace Victoire\ListBundle\Widget\Manager;

use Victoire\ListBundle\Entity\WidgetListItem;

class WidgetListItemManager
{

    protected $container;

    /**
     * constructor
     *
     * @param ServiceContainer $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * render the item
     * @param WidgetListItem $widget
     *
     * @return widget show
     */
    public function render(WidgetListItem $widget)
    {
        $entity = $widget->getEntity()->{'get' . $widget->getBusinessEntitiesName()}();

        return $this->container->get('victoire_templating')->render(
            "VictoireListBundle:Widget:list/showItem.html.twig",
            array(
                "item" => $widget,
                "preview_mode" => true,
                "entity_name" => $entity->__toString(),
                "entity_id" => $entity->getId(),
                "entity" => $widget->getBusinessEntitiesName(),
            )
        );
    }
}
