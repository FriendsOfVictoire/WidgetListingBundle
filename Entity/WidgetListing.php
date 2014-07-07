<?php
namespace Victoire\Widget\ListingBundle\Entity;

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
     * @ORM\OneToMany(targetEntity="WidgetListingItem", mappedBy="listing", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     *
     */
    protected $items;

    /**
     * To string function
     * @return  string A string which represents the instance
     */
    public function __toString()
    {
        return sprintf('#%s - Liste de la page "%s"', $this->getId(), $this->getPage()->getTitle());
    }

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
     *
     * @return WidgetListing
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $item->setListing($this);
            $item->setPage($this->getPage());
            $item->setSlot($this->getSlot());
        }
        $this->items = $items;

        return $this;
    }

    /**
     * Add items
     *
     * @param \Victoire\Widget\ListingBundle\Entity\WidgetListingItem $item
     *
     * @return WidgetListing
     */
    public function addItem(\Victoire\Widget\ListingBundle\Entity\WidgetListingItem $item)
    {
        $item->setListing($this);
        $item->setPage($this->getPage());
        $item->setSlot($this->getSlot());
        $item->setMode($this->getMode());
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Victoire\Widget\ListingBundle\Entity\WidgetListingItem $items
     */
    public function removeItem(\Victoire\Widget\ListingBundle\Entity\WidgetListingItem $items)
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
