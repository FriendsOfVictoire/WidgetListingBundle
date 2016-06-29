<?php

namespace Victoire\Widget\ListingBundle\Resolver;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;
use Victoire\Bundle\QueryBundle\Helper\QueryHelper;
use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Victoire\Widget\ListingBundle\Entity\WidgetListing;

class WidgetListingContentResolver extends BaseWidgetContentResolver
{
    protected $request;
    protected $filters;

    /**
     * $filters is not cast because it can be null.
     *
     * @param RequestStack $requestStack [description]
     * @param array        $filters      [description]
     */
    public function __construct(RequestStack $requestStack, array $filters)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->filters = $filters;
        $this->currentPage = 1;
    }

    /**
     * Get the content of the widget for the query mode.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetQueryContent(Widget $widget)
    {
        $parameters = $this->getWidgetStaticContent($widget);
        $maxResults = $widget->getMaxResults();
        $randomResults = $widget->isRandomResults();

        // Use pager only if maxResult is set and random order is not asked
        if ($maxResults && is_int($maxResults) && !$randomResults) {
            $filterBuilder = $this->getWidgetQueryBuilder($widget);
            $adapter = new DoctrineORMAdapter($filterBuilder->getQuery(), false);
            $pager = new Pagerfanta($adapter);
            $pager->setMaxPerPage($widget->getMaxResults());
            $pager->setCurrentPage($this->request->get('page', $this->currentPage));
            $items = $pager->getCurrentPageResults();

            return array_merge($parameters, ['items' => $items, 'pager' => $pager]);
        }

        /* @var $qb QueryBuilder */
        $qb = $this->getWidgetQueryBuilder($widget);
        $entities = $qb->getQuery()->getResult();

        // Random order
        if ($randomResults) {
            shuffle($entities);
        }

        // Max result in php
        if ($maxResults && is_int($maxResults)) {
            $entities = array_slice($entities, 0, $maxResults);
        }

        $parameters['items'] = $entities;

        return $parameters;
    }

    /**
     * Get the widget query result.
     *
     * @param Widget $widget The widget
     *
     * @return array The list of entities
     */
    public function getWidgetQueryBuilder(Widget $widget)
    {
        /** @var QueryHelper $queryHelper */
        $queryHelper = $this->queryHelper;

        //get the base query
        $itemsQueryBuilder = $queryHelper->getQueryBuilder($widget, $this->entityManager);

        if ($this->filters !== null) {
            $request = $this->request;
            $filters = $request->query->get('filter');

            //the id is an integer
            $listId = intval($filters['listing']);

            //if the filters is the widget id
            if ($listId === $widget->getId()) {
                unset($filters['listing']);

                //we parse the filters
                foreach ($this->filters as $name => $filter) {
                    if (!empty($filters[$name])) {
                        $filter->buildQuery($itemsQueryBuilder, $filters[$name]);
                        $widget->filters[$name] = $filter;
                    }
                }
            }
        }

        // Add the query of the widget
        $queryBuilder = $queryHelper->buildWithSubQuery($widget, $itemsQueryBuilder, $this->entityManager);

        // Filter only visibleOnFront
        return $queryBuilder->andWhere('main_item.visibleOnFront = true');
    }

    /**
     * Get the business entity content
     * If we're in a Business Entity context (current entity),
     * \ we'll open the default page in the current entity's page
     * \ if page get parameter is not defined.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetBusinessEntityContent(Widget $widget)
    {
        $entity = $widget->getEntity();
        $parameters = $this->getWidgetQueryContent($widget);

        $this->populateParametersWithWidgetFields($widget, $entity, $parameters);
        if (!empty($parameters['pager']) && !$this->request->get('page')) {
            /** @var Pagerfanta $_pager */
            $_pager = $parameters['pager'];
            for ($i = 1; $i <= $_pager->getNbPages(); $i++) {
                /* @var WidgetListing $widget */
                $this->currentPage = $i;
                $_parameters = $this->getWidgetQueryContent($widget);
                $_pager = $_parameters['pager'];
                foreach ($_pager->getCurrentPageResults() as $_item) {
                    if ($_item === $entity) {
                        $parameters['pager'] = $_pager;
                        $parameters['items'] = $_pager->getCurrentPageResults();
                        break;
                    }
                }
            }
        }

        return $parameters;
    }
}
