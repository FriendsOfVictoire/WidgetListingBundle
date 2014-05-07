<?php
namespace Victoire\ListingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Victoire\Bundle\CoreBundle\Entity\Widget;

/**
 * WidgetListing
 *
 * @ORM\Table("cms_widget_listing")
 * @ORM\Entity
 */
class WidgetListing extends Widget
{


    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="WidgetListingItem", mappedBy="listing", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     */
    private $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set items
     *
     * @param array $items
     * @return WidgetListing
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $item->setListing($this);
        }
        $this->items = $items;

        return $this;
    }
    /**
     * Add items
     *
     * @param \Victoire\ListingBundle\Entity\WidgetListingItem $items
     * @return WidgetListing
     */
    public function addItem(\Victoire\ListingBundle\Entity\WidgetListingItem $item)
    {
        $item->setListing($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Victoire\ListingBundle\Entity\WidgetListingItem $items
     */
    public function removeItem(\Victoire\ListingBundle\Entity\WidgetListingItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
