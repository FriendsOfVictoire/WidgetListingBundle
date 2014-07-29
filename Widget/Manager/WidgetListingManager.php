<?php
namespace Victoire\Widget\ListingBundle\Widget\Manager;

use Victoire\Bundle\CoreBundle\Widget\Managers\BaseWidgetManager;
use Victoire\Bundle\CoreBundle\Entity\Widget;
use Victoire\Bundle\CoreBundle\Widget\Managers\WidgetManagerInterface;

use Victoire\Widget\ListingBundle\Entity\WidgetListingItem;

/**
 * CRUD operations on WidgetRedactor Widget
 *
 * The widget view has two parameters: widget and content
 *
 * widget: The widget to display, use the widget as you wish to render the view
 * content: This variable is computed in this WidgetManager, you can set whatever you want in it and display it in the show view
 *
 * The content variable depends of the mode: static/businessEntity/entity/query
 *
 * The content is given depending of the mode by the methods:
 *  getWidgetStaticContent
 *  getWidgetBusinessEntityContent
 *  getWidgetEntityContent
 *  getWidgetQueryContent
 *
 * So, you can use the widget or the content in the show.html.twig view.
 * If you want to do some computation, use the content and do it the 4 previous methods.
 *
 * If you just want to use the widget and not the content, remove the method that throws the exceptions.
 *
 * By default, the methods throws Exception to notice the developer that he should implements it owns logic for the widget
 *
 */
class WidgetListingManager extends BaseWidgetManager implements WidgetManagerInterface
{
    /**
     * Get the content of the widget for the query mode
     *
     * @param  Widget     $widget
     * @throws \Exception
     */
    protected function getWidgetQueryContent(Widget $widget)
    {
        $items = $this->getWidgetQueryResults($widget);

        $widgetItems = array();

        //parse the results
        foreach ($items as $item) {
            //create temporary widgetListing item for the render
            $itemWidget = new WidgetListingItem();

            //set the entity found
            $itemWidget->setEntity($item);
            //simulate the entity mode
            $itemWidget->setMode(Widget::MODE_ENTITY);
            //add it to the array
            $widgetItems[] = $itemWidget;
            unset($itemWidget);
        }

        //set the array to the current widget
        $widget->setItems($widgetItems);
    }

    /**
     * Get the widget query result
     *
     * @param Widget $widget The widget
     *
     * @return array The list of entities
     */
    protected function getWidgetQueryResults(Widget $widget)
    {
        $queryHelper = $this->get('victoire_query.query_helper');

        //get the base query
        $itemsQueryBuilder = $queryHelper->getQueryBuilder($widget);

        if ($this->container->has('victoire_core.filter_chain')) {
            $request = $this->container->get('request');
            $filters = $request->query->get('victoire_form_filter');

            //the id is an integer
            $listId = intval($filters['listing']);

            //if the filters is the widget id
            if ($listId === $widget->getId()) {
                unset($filters['listing']);

                $filterChains = $this->container->get('victoire_core.filter_chain');

                //we parse the filters
                foreach ($filterChains->getFilters() as $name => $filter) {
                    if (!empty($filters[$name])) {
                        $filter->buildQuery($itemsQueryBuilder, $filters[$name]);
                        $widget->filters[$name] = $filter->getFilters($filters[$name]);
                    }
                }
            }
        }

        //add the query of the widget
        $items = $queryHelper->getResultsAddingSubQuery($widget, $itemsQueryBuilder);

        return $items;
    }
}
