<?php

namespace Victoire\Widget\ListingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Victoire\Bundle\CoreBundle\Annotations as VIC;
use Victoire\Bundle\WidgetBundle\Entity\WidgetItemInterface;

/**
 * WidgetListingItem.
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\Table("vic_widget_listing_item")
 * @ORM\Entity
 */
class WidgetListingItem implements WidgetItemInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @VIC\ReceiverProperty("textable")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="WidgetListing", inversedBy="items")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $listing;

    /**
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 0;

    /**
     * Set the id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the id.
     *
     * @return int The id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get fields.
     *
     * @return string
     */
    public function getFields()
    {
        $fields = [];

        //get the listing
        $listing = $this->getListing();

        if ($listing !== null) {
            $fields = $listing->getFields();
        }

        return $fields;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return WidgetListingItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set listing.
     *
     * @param string $listing
     *
     * @return WidgetListingItem
     */
    public function setListing($listing)
    {
        $this->listing = $listing;

        return $this;
    }

    /**
     * Get listing.
     *
     * @return string
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position.
     *
     * @param int $position The position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
