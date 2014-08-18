<?php
namespace Victoire\Widget\ListingBundle\Resolver;

use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;

class WidgetListingContentResolver extends BaseWidgetContentResolver
{

    /**
     * Get the content of the widget for the query mode
     *
     * @param Widget $widget
     *
     * @return string
     *
     */
    public function getWidgetQueryContent(Widget $widget)
    {
        $parameters = $this->getWidgetStaticContent($widget);

        $entities = $this->getWidgetQueryBuilder($widget)
                       ->getQuery()
                       ->getResult();

        $parameters['items'] = $entities;

        return $parameters;
    }

}
