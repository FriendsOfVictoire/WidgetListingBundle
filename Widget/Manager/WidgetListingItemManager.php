<?php
namespace Victoire\ListingBundle\Widget\Manager;

use Victoire\ListingBundle\Entity\WidgetListingItem;

class WidgetListingItemManager
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
     * @param WidgetListingItem $widget
     *
     * @return widget show
     */
    public function render(WidgetListingItem $widget)
    {
        $entity = $widget->getEntity()->{'get' . $widget->getBusinessEntityName()}();

        return $this->container->get('victoire_templating')->render(
            "VictoireListingBundle:Widget:listing/showItem.html.twig",
            array(
                "item" => $widget,
                "preview_mode" => true,
                "entity_name" => $entity->__toString(),
                "entity_id" => $entity->getId(),
                "entity" => $widget->getBusinessEntityName(),
            )
        );
    }
}
