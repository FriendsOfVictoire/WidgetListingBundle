<?php
namespace Victoire\Widget\ListingBundle\Widget\Manager;

use Victoire\Bundle\CoreBundle\Widget\Managers\BaseWidgetManager;
use Victoire\Bundle\CoreBundle\Entity\Widget;
use Victoire\Bundle\CoreBundle\Widget\Managers\WidgetManagerInterface;
use Victoire\Bundle\CoreBundle\VictoireCmsEvents;
use Victoire\Bundle\CoreBundle\Event\WidgetRenderEvent;

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
class WidgetListingItemManager extends BaseWidgetManager implements WidgetManagerInterface
{
    /**
     * Get the static content of the widget
     *
     * @param Widget $widget
     *
     * @return string The static content
     *
     * @SuppressWarnings checkUnusedFunctionParameters
     */
    protected function getWidgetStaticContent(Widget $widget)
    {
        //in static mode nothing is done
        $content = '';

        return $content;
    }

    /**
     * Get the business entity content
     * @param  Widget   $widget
     * @return Ambigous <string, unknown, \Victoire\Bundle\CoreBundle\Widget\Managers\mixed, mixed>
     *
     * @throws \Exception
     */
    protected function getWidgetBusinessEntityContent(Widget $widget)
    {
        $mode = $widget->getMode();
        throw new \Exception('The mode ['.$mode.'] is not yet supported by the widget manager. Widget ID:['.$widget->getId().']');
    }

    /**
     * Get the content of the widget by the entity linked to it
     *
     * @param Widget $widget
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getWidgetEntityContent(Widget $widget)
    {
        //the result
        $content = '';

        $entity = $widget->getBusinessEntity();

        if ($entity === null) {
            throw new \Exception('The widget ['.$widget->getId().'] has no entity to display.');
        }

        $fields = $widget->getFields();

        if (!isset($fields['title'])) {
            throw new \Exception('The widget ['.$widget->getId().'] does not have the field title in the fields attribute.');
        }
        if (!isset($fields['description'])) {
            throw new \Exception('The widget ['.$widget->getId().'] does not have the field description in the fields attribute.');
        }
        //get the attribute to match
        $titleAttribute = $fields['title'];
        $descriptionAttribute = $fields['description'];

        //get the values from the entities
        $titleValue =  $this->getEntityAttributeValue($entity, $titleAttribute);
        $descriptionValue =  $this->getEntityAttributeValue($entity, $descriptionAttribute);

        //override the title and description of the widget
        $widget->setTitle($titleValue);
        $widget->setDescription($descriptionValue);

        return $content;
    }

    /**
     * Get the content of the widget for the query mode
     *
     * @param  Widget     $widget
     * @throws \Exception
     */
    protected function getWidgetQueryContent(Widget $widget)
    {
        $mode = $widget->getMode();
        throw new \Exception('The mode ['.$mode.'] is not yet supported by the widget manager. Widget ID:['.$widget->getId().']');
    }

    /**
     * render the WidgetRedactor
     * @param WidgetRedactor $widget
     *
     * @return widget show
     */
    public function render(Widget $widget, $entity = null)
    {
        //the templating service
        $templating = $this->container->get('victoire_templating');

        //the content of the widget
        $content = $this->getWidgetContent($widget);

        //the template displayed is in the widget bundle
        $templateName = $this->getTemplateName('showItem');

        return $templating->render(
            $templateName,
            array(
                "widget" => $widget,
                "content" => $content
            )
        );
    }

    /**
     * render a widget
     *
     * @param Widget  $widget
     * @param boolean $addContainer
     *
     * @return template
     */
    public function renderContainer(Widget $widget, $addContainer = false, $entity = null)
    {
        $html = '';
        $dispatcher = $this->container->get('event_dispatcher');

        $dispatcher->dispatch(VictoireCmsEvents::WIDGET_PRE_RENDER, new WidgetRenderEvent($widget, $html));

        $html .= $this->render($widget, $entity);

        if ($addContainer) {
            $html = "<div class='widget-container' id='vic-widget-".$widget->getId()."-container'>".$html.'</div>';
        }

        $dispatcher->dispatch(VictoireCmsEvents::WIDGET_POST_RENDER, new WidgetRenderEvent($widget, $html));

        return $html;
    }
}
