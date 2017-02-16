<?php

namespace Victoire\Widget\ListingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Victoire\Bundle\WidgetBundle\Entity\Widget;

/**
 * WidgetListing.
 *
 * @ORM\Table("vic_widget_listing")
 * @ORM\Entity
 */
class WidgetListing extends Widget
{
    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="WidgetListingItem", mappedBy="listing", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $items;

    /**
     * @ORM\ManyToOne(targetEntity="Victoire\Bundle\BusinessPageBundle\Entity\BusinessTemplate")
     */
    protected $targetPattern;

    /**
     * @var int
     *
     * @ORM\Column(name="maxResults", type="integer", nullable=true)
     */
    protected $maxResults;

    /**
     * @var bool
     *
     * @ORM\Column(name="randomResults", type="boolean", nullable=true)
     */
    protected $randomResults;

    /**
     * To string function.
     *
     * @return string A string which represents the instance
     */
    public function __toString()
    {
        return sprintf('Widget Listing #%s', $this->getId());
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set items.
     *
     * @param array $items
     *
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
     * Add items.
     *
     * @param \Victoire\Widget\ListingBundle\Entity\WidgetListingItem $item
     *
     * @return WidgetListing
     */
    public function addItem(\Victoire\Widget\ListingBundle\Entity\WidgetListingItem $item)
    {
        $item->setListing($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items.
     *
     * @param \Victoire\Widget\ListingBundle\Entity\WidgetListingItem $items
     */
    public function removeItem(\Victoire\Widget\ListingBundle\Entity\WidgetListingItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get targetPattern.
     *
     * @return string
     */
    public function getTargetPattern()
    {
        return $this->targetPattern;
    }

    /**
     * Set targetPattern.
     *
     * @param string $targetPattern
     *
     * @return $this
     */
    public function setTargetPattern($targetPattern)
    {
        $this->targetPattern = $targetPattern;

        return $this;
    }

    /**
     * Set maxResults.
     *
     * @param string $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;

        return $this;
    }

    /**
     * Get maxResults.
     *
     * @return string
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @return bool
     */
    public function isRandomResults()
    {
        return $this->randomResults;
    }

    /**
     * @param bool $randomResults
     */
    public function setRandomResults($randomResults)
    {
        $this->randomResults = $randomResults;
    }
}
