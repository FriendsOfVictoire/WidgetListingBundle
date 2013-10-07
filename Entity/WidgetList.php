<?php
namespace Victoire\ListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Victoire\CmsBundle\Entity\Widget;

/**
 * WidgetList
 *
 * @ORM\Table("cms_widget_list")
 * @ORM\Entity
 */
class WidgetList extends Widget
{


    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="WidgetListItem", mappedBy="list", cascade={"persist"})
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
     * @return WidgetList
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $item->setList($this);
        }
        $this->items = $items;

        return $this;
    }
    /**
     * Add items
     *
     * @param \Victoire\ListBundle\Entity\WidgetListItem $items
     * @return WidgetList
     */
    public function addItem(\Victoire\ListBundle\Entity\WidgetListItem $item)
    {
        $item->setList($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Victoire\ListBundle\Entity\WidgetListItem $items
     */
    public function removeItem(\Victoire\ListBundle\Entity\WidgetListItem $items)
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
