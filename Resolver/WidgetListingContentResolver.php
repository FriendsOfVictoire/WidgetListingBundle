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

        $fields = $widget->getFields();
        foreach ($entities as $entity) {
            $this->populateParametersWithWidgetFields($widget, $entity, $parameters['items']);
        }

        return $parameters;
    }

}
