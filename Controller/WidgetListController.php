<?php
namespace Victoire\ListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Victoire\ListBundle\Entity\WidgetListItem;
use Victoire\CmsBundle\Cached\Entity\EntityProxy;


/**
 * Widget list controller
 *
 */
class WidgetListController extends Controller
{

    /**
     * This method is used in ajax to show list items when a list is created
     *
     * @param Request $request
     * @return Response The renderig of posted items
     *
     * @Template()
     * @Method("POST")
     */
    public function showAction(Request $request)
    {
        $form = $request->request->get('appventus_venatorcmsbundle_widgetlisttype');

        $widgetsHtml = array();
        $fields = $form['fields'];
        if (array_key_exists('items', $form)) {
            // for each items added to the list
            foreach ($form['items'] as $entityId => $item) {

                $item = $item['entity'];
                $itemKey = array_keys($item);
                $entityName = $itemKey[0];
                $businessClasses = $this->get('victoire_cms.annotation_reader')->getBusinessClasses();
                $class = $businessClasses[$entityName];
                $id = $item[$entityName];
                // we get associated entity in db
                $entity = $this->get('doctrine.orm.entity_manager')->getRepository($class)->findOneById($id);

                // build the ListItem
                $widget = new WidgetListItem();
                $proxy = new EntityProxy();
                $proxy->{'set' . $entityName}($entity);
                $widget->setEntity($proxy);
                $widget->setBusinessEntitiesName($entityName);
                foreach ($fields as $field => $entityField) {
                    $widget->{'set' . ucfirst($field)}($entity->{'get' . ucfirst($entityField)}());
                }
                // and render each of them
                $widgetsHtml[$entityId] = $this->get('widget_listitem_manager')->render($widget);
            }

        }
        // print_r($widgetsHtml);exit;
        ksort($widgetsHtml);

        array_push($widgetsHtml, $widgetsHtml['__name__']);
        unset($widgetsHtml['__name__']);

        return new Response(implode("", $widgetsHtml));

    }
}
