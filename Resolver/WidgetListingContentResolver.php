<?php
namespace Victoire\Widget\ListingBundle\Resolver;

use Symfony\Component\HttpFoundation\RequestStack;
use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Victoire\Widget\FilterBundle\Filter\Chain\FilterChain;

class WidgetListingContentResolver extends BaseWidgetContentResolver
{

    protected $request;
    protected $filterChain;

    /**
     * $filterChain is not cast because it can be null
     * @param RequestStack $requestStack [description]
     * @param FilterChain  $filterChain  [description]
     */
    public function __construct(RequestStack $requestStack, FilterChain $filterChain = null)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->filterChain = $filterChain;
    }
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

    /**
     * Get the widget query result
     *
     * @param Widget $widget The widget
     *
     * @return array The list of entities
     */
    public function getWidgetQueryBuilder(Widget $widget)
    {
        $queryHelper = $this->queryHelper;

        //get the base query
        $itemsQueryBuilder = $queryHelper->getQueryBuilder($widget);

        if ($this->filterChain !== null) {
            $request = $this->request;
            $filters = $request->query->get('victoire_form_filter');

            //the id is an integer
            $listId = intval($filters['listing']);

            //if the filters is the widget id
            if ($listId === $widget->getId()) {
                unset($filters['listing']);

                $filterChains = $this->filterChain;

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
        $queryBuilder = $queryHelper->buildWithSubQuery($widget, $itemsQueryBuilder);
        // Filter only visibleOnFront
        return $queryBuilder->andWhere('main_item.visibleOnFront = true');
    }

}
