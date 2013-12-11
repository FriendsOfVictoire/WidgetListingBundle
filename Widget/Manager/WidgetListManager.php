<?php
namespace Victoire\ListBundle\Widget\Manager;

use Victoire\ListBundle\Form\WidgetListType;
use Victoire\ListBundle\Entity\WidgetList;
use Victoire\CmsBundle\Event±WidgetQueryEvent;
use Victoire\CmsBundle\VictoireCmsEvents;
use Victoire\CmsBundle\Event\WidgetQueryEvent;

class WidgetListManager
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
     * create a new WidgetList
     * @param Page   $page
     * @param string $slot
     *
     * @return $widget
     */
    public function newWidget($page, $slot)
    {
        $widget = new WidgetList();
        $widget->setPage($page);
        $widget->setSlot($slot);

        return $widget;
    }
    /**
     * render the WidgetList
     * @param Widget $widget
     *
     * @return widget show
     */
    public function render($widget, $page)
    {


        $listId = $this->container->get('request')->query->get('filter')['list'];
        $dispatcher = $this->container->get('event_dispatcher');

        $itemsQueryBuilder = $this->container->get('doctrine.orm.entity_manager')
             ->createQueryBuilder()
             ->select('item')
             ->from('VictoireListBundle:WidgetListItem', 'item')
             ->join('item.list', 'list')
             ->where('list.id = :list')
             ->setParameter('list', $listId);


        $dispatcher->dispatch(VictoireCmsEvents::WIDGET_POST_QUERY, new WidgetQueryEvent($widget, $itemsQueryBuilder, $this->container->get('request')));

        $items = $itemsQueryBuilder
                 ->getQuery()
                 ->getResult();

        return $this->container->get('victoire_templating')->render(
            "VictoireListBundle:Widget:list/show.html.twig",
            array(
                "widget" => $widget,
                "items" => $items,
                "page" => $page
            )
        );
    }

    /**
     * render WidgetList form
     * @param Form           $form
     * @param WidgetList     $widget
     * @param BusinessEntity $entity
     * @return form
     */
    public function renderForm($form, $widget, $entity = null)
    {

        return $this->container->get('victoire_templating')->render(
            "VictoireListBundle:Widget:list/edit.html.twig",
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
     * @param WidgetList $widget
     * @param string     $entityName
     * @param string     $namespace
     * @return $form
     */
    public function buildForm($widget, $entityName = null, $namespace = null)
    {
        $form = $this->container->get('form.factory')->create(new WidgetListType($entityName, $namespace), $widget);

        return $form;
    }

    /**
     * create form new for WidgetList
     * @param Form           $form
     * @param WidgetList     $widget
     * @param string         $slot
     * @param Page           $page
     * @param string         $entity
     *
     * @return new form
     */
    public function renderNewForm($form, $widget, $slot, $page, $entity = null)
    {

        return $this->container->get('victoire_templating')->render(
            "VictoireListBundle:Widget:list/new.html.twig",
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
