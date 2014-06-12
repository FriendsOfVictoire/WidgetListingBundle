<?php
namespace Victoire\Widget\ListingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Victoire\Widget\ListingBundle\Entity\WidgetListingItem;
use Victoire\Bundle\CoreBundle\Cached\Entity\EntityProxy;


/**
 * Widget listing controller
 *
 * @Route("/victoire-dcms/widget-listing")
 *
 */
class WidgetListingController extends Controller
{
    /**
     * This method is used in ajax to show list items when a listing is created
     *
     * @param Request $request
     * @return Response The renderig of posted items
     *
     * @Route("/show", name="victoire_widgetlisting_widgetlisting_show", options={"expose"=true})
     *
     * @Template()
     * @Method("POST")
     */
    public function showAction(Request $request)
    {
        $form = $request->request->get('victoire_widget_form_listing');

        $widgetsHtml = array();

        $fields = $form['fields'];

        if (array_key_exists('items', $form)) {
            // for each items added to the listing
            foreach ($form['items'] as $entityId => $item) {

                $item = $item['entity_proxy'];
                $itemKey = array_keys($item);
                $entityName = $itemKey[0];
                $businessClasses = $this->get('victoire_core.annotation_reader')->getBusinessClasses();
                $class = $businessClasses[$entityName];
                $id = $item[$entityName];
                // we get associated entity in db
                $entity = $this->get('doctrine.orm.entity_manager')->getRepository($class)->findOneById($id);

                // build the ListItem
                $widget = new WidgetListingItem();
                $proxy = new EntityProxy();
                $proxy->{'set' . $entityName}($entity);
                $widget->setEntity($proxy);
                $widget->setBusinessEntityName($entityName);
                foreach ($fields as $field => $entityField) {
                    $widget->{'set' . ucfirst($field)}($entity->{'get' . ucfirst($entityField)}());
                }
                // and render each of them
                $widgetsHtml[$entityId] = $this->get('widget_listingitem_manager')->render($widget);
            }

        }

        ksort($widgetsHtml);

        array_push($widgetsHtml, $widgetsHtml['__name__']);
        unset($widgetsHtml['__name__']);

        return new Response(implode("", $widgetsHtml));

    }
}
