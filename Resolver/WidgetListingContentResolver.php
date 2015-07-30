<?php
namespace Victoire\Widget\ListingBundle\Resolver;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Victoire\Bundle\FilterBundle\Filter\Chain\FilterChain;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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
        $maxResults = $widget->getMaxResults();
        $randomResults = $widget->isRandomResults();

        // Use pager only if maxResult is set and random order is not asked
        if ($maxResults && is_integer($maxResults) && !$randomResults) {

            $filterBuilder = $this->getWidgetQueryBuilder($widget);
            $adapter = new DoctrineORMAdapter($filterBuilder->getQuery());
            $pager = new Pagerfanta($adapter);
            $pager->setMaxPerPage($widget->getMaxResults());
            $pager->setCurrentPage($this->request->get('page') ?: 1);
            $items = $pager->getCurrentPageResults();

            return array_merge($parameters, array('items' => $items, 'pager' => $pager));

        }

        /* @var $qb QueryBuilder */
        $qb = $this->getWidgetQueryBuilder($widget);
        $entities = $qb->getQuery()->getResult();

        // Random order
        if($randomResults) {
            shuffle($entities);
        }

        // Max result in php
        if ($maxResults && is_integer($maxResults)) {
            $entities = array_slice($entities, 0, $maxResults);
        }

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
            $filters = $request->query->get('filter');

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

        // Add the query of the widget
        $queryBuilder = $queryHelper->buildWithSubQuery($widget, $itemsQueryBuilder);

        // Filter only visibleOnFront
        return $queryBuilder->andWhere('main_item.visibleOnFront = true');
    }

}
