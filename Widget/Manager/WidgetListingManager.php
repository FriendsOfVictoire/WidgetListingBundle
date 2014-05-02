<?php
namespace Victoire\ListingBundle\Widget\Manager;

use Victoire\ListingBundle\Form\WidgetListingType;
use Victoire\ListingBundle\Entity\WidgetListing;
use Victoire\CmsBundle\Event±WidgetQueryEvent;
use Victoire\CmsBundle\VictoireCmsEvents;
use Victoire\CmsBundle\Event\WidgetQueryEvent;

class WidgetListingManager
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
     * create a new WidgetListing
     * @param Page   $page
     * @param string $slot
     *
     * @return $widget
     */
    public function newWidget($page, $slot)
    {
        $widget = new WidgetListing();
        $widget->setPage($page);
        $widget->setSlot($slot);

        return $widget;
    }
    /**
     * render the WidgetListing
     * @param Widget $widget
     *
     * @return widget show
     */
    public function render($widget)
    {


        $listingId = $this->container->get('request')->query->get('filter')['listing'];
        $dispatcher = $this->container->get('event_dispatcher');
        $em = $this->container->get('doctrine.orm.entity_manager');
        $query = "";
        if ($widget->getQuery() !== null) {

            $itemsQueryBuilder = $em
                 ->createQueryBuilder()
                 ->select('item')
                 ->from($widget->getBusinessClass(), 'item');

            $query = $widget->getQuery();
        } else {
            $itemsQueryBuilder = $this->container->get('doctrine.orm.entity_manager')
                 ->createQueryBuilder()
                 ->select('item')
                 ->from('VictoireListingBundle:WidgetListingItem', 'item')
                 ->join('item.listing', 'listing')
                 ->where('listing.id = :listing')
                 ->setParameter('listing', $listingId);
        }


        $dispatcher->dispatch(VictoireCmsEvents::WIDGET_POST_QUERY, new WidgetQueryEvent($widget, $itemsQueryBuilder, $this->container->get('request')));

        $itemsQuery = $itemsQueryBuilder->getQuery()->getDQL() . " " . $query;

        $items = $em->createQuery($itemsQuery)
                        ->setParameters($itemsQueryBuilder->getParameters())
                        ->getResult();

        return $this->container->get('victoire_templating')->render(
            "VictoireListingBundle::show.html.twig",
            array(
                "widget" => $widget,
                "items" => $items
            )
        );
    }

    /**
     * render WidgetListing  form
     * @param Form           $form
     * @param WidgetListing  $widget
     * @param BusinessEntity $entity
     * @return form
     */
    public function renderForm($form, $widget, $entity = null)
    {

        return $this->container->get('victoire_templating')->render(
            "VictoireListingBundle::edit.html.twig",
            array(
                "widget" => $widget,
                'form'   => $form->createView(),
                'id'     => $widget->getId(),
                'entity' => $entity
            )
        );
    }

    /**
     * create a form with given widget
     * @param WidgetListing $widget
     * @param string        $entityName
     * @param string        $namespace
     * @return $form
     */
    public function buildForm($widget, $entityName = null, $namespace = null)
    {
        $form = $this->container->get('form.factory')->create(new WidgetListingType($entityName, $namespace), $widget);

        return $form;
    }

    /**
     * create form new for WidgetListing
     * @param Form           $form
     * @param WidgetListing  $widget
     * @param string         $slot
     * @param Page           $page
     * @param string         $entity
     *
     * @return new form
     */
    public function renderNewForm($form, $widget, $slot, $page, $entity = null)
    {

        return $this->container->get('victoire_templating')->render(
            "VictoireListingBundle::new.html.twig",
            array(
                "widget"          => $widget,
                'form'            => $form->createView(),
                "slot"            => $slot,
                "entity"          => $entity,
                "renderContainer" => true,
                "page"            => $page
            )
        );
    }
}
