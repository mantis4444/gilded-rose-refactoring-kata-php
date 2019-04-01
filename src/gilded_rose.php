<?php

require_once 'GildedRose/Item.php';
require_once 'GildedRose/Item/Enhanced.php';
require_once 'GildedRose/Item/Enhanced/Backstage.php';
require_once 'GildedRose/Item/Enhanced/Brie.php';
require_once 'GildedRose/Item/Enhanced/Conjured.php';
require_once 'GildedRose/Item/Enhanced/Sulfuras.php';
require_once 'GildedRose/Item/Enhanced/Normal.php';

/**
 * Gilded Rose class
 */
class GildedRose {

    /**
     * Items
     * @var array
     */
    private $items;

    /**
     * Contruct
     * @param array &$items
     */
    function __construct(&$items) {
        $this->set_items($items);
    }

    /**
     * Update all items
     * @return GildedRose
     */
    public function update_quality(): GildedRose {
        foreach ($this->get_items() as $item) {
            $item->update();
        }
        return $this;
    }

    /**
     * Get items
     * @return array
     */
    public function get_items(): Array {
        return $this->items;
    }

    /**
     * Set items. Enhance if needed.
     * @param array $items
     */
    private function set_items(&$items): GildedRose {
        $this->items = &$items;
        $this->enhance_items();
        return $this;
    }

    /**
     * Enhance items to their proper class
     * @return GildedRose
     */
    private function enhance_items(): GildedRose {
        $enhanced_items = array();

        foreach ($this->get_items() as $key => $item) {
            // Create certain type item
            switch ($item->name) {
                case ItemEnhanced::TYPE_BACKSTAGE:
                    $enhanced_items[$key] = new ItemEnhancedBackstage($item->name, $item->sell_in, $item->quality);
                    break;
                case ItemEnhanced::TYPE_CONJURED:
                    $enhanced_items[$key] = new ItemEnhancedConjured($item->name, $item->sell_in, $item->quality);
                    break;
                case ItemEnhanced::TYPE_BRIE:
                    $enhanced_items[$key] = new ItemEnhancedBrie($item->name, $item->sell_in, $item->quality);
                    break;
                case ItemEnhanced::TYPE_SULFURAS:
                    $enhanced_items[$key] = new ItemEnhancedSulfuras($item->name, $item->sell_in, $item->quality);
                    break;
                default:
                    $enhanced_items[$key] = new ItemEnhancedNormal($item->name, $item->sell_in, $item->quality);
            }
        }

        $this->items = $enhanced_items;
        return $this;
    }

}
