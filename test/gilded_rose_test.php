<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once 'gilded_rose.php';

class GildedRoseTest extends TestCase
{

    /**
     * The GildedRose app variable
     * @var GildedRose
     */
    private $app;

    /**
     * Test if there is no quality change for Sulfuras items
     * @return void
     */
    public function testNoQualityChangeForSulfurasItems(): void
    {
        $itemName = ItemEnhanced::TYPE_SULFURAS;
        $quality  = ItemEnhanced::TYPE_SULFURAS_QUALITY;
        $this->_setApp(array(
            new Item($itemName, -1, $quality),
            new Item($itemName,  0, $quality),
            new Item($itemName,  1, $quality)
        ));

        $this->_updateQuality();

        foreach ($this->_getApp()->get_items() as $item) {
            $this->_isSulfurasItemQualityCorrect($item->quality);
        }
    }

    /**
     * Test if quality does not exceed max
     * @return void
     */
    public function testIfQualityIsNotOverMax(): void
    {
        $days = 10;
        $itemBrieName = ItemEnhanced::TYPE_BRIE;
        $itemBackName = ItemEnhanced::TYPE_BACKSTAGE;
        $this->_setApp(array(
            new Item($itemBrieName, -5,  0),
            new Item($itemBrieName,  2, 50),
            new Item($itemBrieName, 10, 45),
            new Item($itemBackName, -5, 49),
            new Item($itemBackName, 10, 40),
            new Item($itemBackName,  1,  5)
        ));

        $this->_updateQuality($days);

        foreach ($this->_getApp()->get_items() as $item) {
            $this->_isQualityNotOverMax($item);
        }
    }

    /**
     * Test if quality does not go under min
     * @return void
     */
    public function testIfQualityIsNotUnderMin(): void
    {
        $days = 10;
        $regularItemName = '+5 Dexterity Vest';
        $this->_setApp(array(
            new Item($regularItemName, 10,   0),
            new Item($regularItemName, 10,   5),
            new Item($regularItemName,  2,   1),
            new Item($regularItemName, -5,   5)
        ));

        $this->_updateQuality($days);

        foreach ($this->_getApp()->get_items() as $item) {
            $this->_isQualityNotUnderMin($item);
        }
    }

    /**
     * Test if quality increase while getting old
     * @return void
     */
    public function testIfQualityIncreaseWhileGettingOld(): void
    {
        $itemBrieName = ItemEnhanced::TYPE_BRIE;
        $itemBackName = ItemEnhanced::TYPE_BACKSTAGE;
        $this->_setApp(array(
            new Item($itemBrieName, 50, 5),
            new Item($itemBrieName,  2, 0),
            new Item($itemBackName, 20, 5),
            new Item($itemBackName, 10, 5),
            new Item($itemBackName,  5, 5)
        ));
        $this->_isItemParamIncrement('quality');
    }

    /**
     * Test if backstage passes quality increase correctly when
     * sell in is >10, 6-10 and 1-5
     * @return void
     */
    public function testIfBackstageQualityIncreaseCorrectly(): void
    {
        $backstageQualityIncreaseCases = array(
            // Case when quality should increase normally by 1
            array(
                'item' => new Item(ItemEnhanced::TYPE_BACKSTAGE, 20, 5),
                'expected_incr' => 1,
            ),
            // Case when quality should increase by 2 when sell in time is 10 days or less
            array(
                'item' => new Item(ItemEnhanced::TYPE_BACKSTAGE, 10, 5),
                'expected_incr' => 2,
            ),
            // Case when quality should increase by 3 when sell in time is 5 days or less
            array(
                'item' => new Item(ItemEnhanced::TYPE_BACKSTAGE, 5, 5),
                'expected_incr' => 3,
            )
        );

        foreach ($backstageQualityIncreaseCases as $backstageIncreaseCase) {
            $this->_setApp(array($backstageIncreaseCase['item']));
            $this->_isItemParamIncrDecrBy('quality', $backstageIncreaseCase['expected_incr']);
        }
    }

    /**
     * Test if items quality decrease correctly
     * @return void
     */
    public function testIfItemQualityDecreaseCorrectly(): void
    {
        $regularItemName = '+5 Dexterity Vest';
        $itemQualityDecreaseCases = array(
            // Normal case when quality decrease by 1
            array(
                'item' => new Item($regularItemName, 20, 5),
                'expected_decr' => -1,
            ),
            // Normal case when quality decrease by 1 with min quality
            array(
                'item' => new Item($regularItemName, 20, 0),
                'expected_decr' => -1,
            ),
            // Normal case when quality decrease by 1 with max quality
            array(
                'item' => new Item($regularItemName, 20, 50),
                'expected_decr' => -1,
            ),
            // When sell in value is negative and quality drops by 2
            array(
                'item' => new Item($regularItemName, -1, 5),
                'expected_decr' => -2,
            ),
            // Case when quality of Backstage passes drops down to 0
            array(
                'item' => new Item(ItemEnhanced::TYPE_BACKSTAGE, 0, 40),
                'expected_decr' => -40,
            ),
        );

        foreach ($itemQualityDecreaseCases as $itemQualityDecreaseCase) {
            $this->_setApp(array($itemQualityDecreaseCase['item']));
            $this->_isItemParamIncrDecrBy('quality', $itemQualityDecreaseCase['expected_decr']);
        }
    }

    /**
     * Check if sell in days decrease
     * @return void
     */
    public function testIfSellInDecr(): void
    {
        $sellIn  =  5;
        $quality = 30;
        $itemSellInDecreaseCases = array(
            array(
                'item' => new Item('+5 Dexterity Vest', $sellIn, $quality),
                'expected_decr' => -1,
            ),
            array(
                'item' => new Item(ItemEnhanced::TYPE_BRIE, $sellIn, $quality),
                'expected_decr' => -1,
            ),
            array(
                'item' => new Item(ItemEnhanced::TYPE_BACKSTAGE, $sellIn, $quality),
                'expected_decr' => -1,
            ),
            array(
                'item' => new Item(ItemEnhanced::TYPE_SULFURAS, $sellIn, $quality),
                'expected_decr' => 0,
            )
        );

        foreach ($itemSellInDecreaseCases as $itemSellInDecreaseCase) {
            $this->_setApp(array($itemSellInDecreaseCase['item']));
            $this->_isItemParamIncrDecrBy('sell_in', $itemSellInDecreaseCase['expected_decr']);
        }
    }

    /**
     * Test if Conjured item quality decrements by 2
     * @return void
     */
    public function testIfConjuredItemQualityDecrBy2(): void
    {
        $sellIn  = 5;
        $quality = 5;
        $this->_setApp(array(
            new Item('Conjured Mana Cake', $sellIn, $quality)
        ));

        $this->_isItemParamIncrDecrBy('quality', -2);
    }

    /**
     * Test if Conjured item quality decrements by 2
     * @return void
     */
    public function testIfConjuredItemQualityDecrOnNegativeSellInBy4(): void
    {
        $sellIn  = -2;
        $quality = 10;
        $this->_setApp(array(
            new Item('Conjured Mana Cake', $sellIn, $quality)
        ));

        $this->_isItemParamIncrDecrBy('quality', -4);
    }

    /**
     * Check if item param increases in one day
     * @return void
     */
    private function _isItemParamIncrement($param): void
    {
        // Get initial item qualities
        $initItemParamValues = $this->_getItemsParamValues($param);

        $this->_updateQuality();

        // Check if quality incremented as expected
        foreach ($this->_getApp()->get_items() as $key => $item) {
            $this->assertGreaterThan(
                $initItemParamValues[$key],
                $item->$param,
                "{$param} did not increment. Expected to be more than {$initItemParamValues[$key]} and the actual value is {$item->$param}"
            );
        }
    }

    /**
     * Check if sell in decreases in one day
     * @return void
     */
    private function _isItemParamDecrement(string $param): void
    {
        // Get initial items parameter values
        $initItemsParam = $this->_getItemsParamValues($param);

        $this->_updateQuality();

        // Check if quality incremented as expected
        foreach ($this->_getApp()->get_items() as $key => $item) {
            $this->assertLessThan(
                $initItemParam[$key],
                $item->$param,
                "{$param} did not decrement. Expected to be more than {$item->$param} and the actual value is {$initItemParam[$key]}"
            );
        }
    }

    /**
     * Is parameter of the item incremented or decremented by exact number
     * @param  string $param      | Item parameter to check
     * @param  int    $incrDecrBy | Expected increment by   | Default: 1
     * @param  int    $days       | Number of days          | Default: 1
     * @return void
     */
    private function _isItemParamIncrDecrBy(string $param, int $incrDecrBy = 1, int $days = 1): void
    {
        // Check if is increment or decrement action
        $incrDecr = $incrDecrBy < 0 ? 'decrement' : 'increment';

        // Get initial item qualities
        $initParamValues = $this->_getItemsParamValues($param);

        $this->_updateQuality($days);

        // Check if quality incremented or decremented as expected
        foreach ($this->_getApp()->get_items() as $key => $item) {
            $expectedValue = $initParamValues[$key] + $incrDecrBy;
            if ($param == 'quality') {
                if ($expectedValue < 0) {
                    $expectedValue = 0;
                } elseif ($expectedValue > 50) {
                    $expectedValue = 50;
                }
            }
            $this->assertEquals(
                $expectedValue,
                $item->$param,
                "{$param} of \"{$item->name}\" item did not {$incrDecr} correctly. With the initial value of {$initParamValues[$key]}, final {$param} is {$item->{$param}}, when it is expected to be {$expectedValue}"
            );
        }
    }

    /**
     * Get items parameter values
     * @param  string $param | Item parameter to check
     * @return void
     */
    private function _getItemsParamValues(string $param): array
    {
        $paramValues = array();
        foreach ($this->_getApp()->get_items() as $key => $item) {
            $paramValues[$key] = $item->$param;
        }
        return $paramValues;
    }

    /**
     * Test if Sulfuras item quality is correct
     * @param  int    $quality
     * @return void
     */
    private function _isSulfurasItemQualityCorrect(int $quality): void
    {
        $this->assertEquals(
            ItemEnhanced::TYPE_SULFURAS_QUALITY,
            $quality,
            "Item \"" . ItemEnhanced::TYPE_SULFURAS
                . "\" quality is {$quality}, which is not equal to "
                . ItemEnhanced::TYPE_SULFURAS_QUALITY
        );
    }

    /**
     * Check if quality of the item did not exceed its max value
     * @param  mixed  $item
     * @return void
     */
    private function _isQualityNotOverMax($item): void
    {
        $this->assertLessThanOrEqual(
            ItemEnhanced::QUALITY_MAX,
            $item->quality,
            "Item \"{$item->name}\" has quality of {$item->quality} which exceeds the max quality "
                . ItemEnhanced::QUALITY_MAX
        );
    }

    /**
     * Check if quality of the item did not go under min
     * @param  mixed  $item
     * @return void
     */
    private function _isQualityNotUnderMin($item): void
    {
        $this->assertGreaterThanOrEqual(
            ItemEnhanced::QUALITY_MIN,
            $item->quality,
            "Item \"{$item->name}\" has quality of {$item->quality} which is under the min quality "
                . ItemEnhanced::QUALITY_MIN
        );
        return;
    }

    /**
     * Update items quality
     * @param  int    $days  | Number of days to update items | Default: 1
     * @return void
     */
    private function _updateQuality(int $days = 1): void
    {
        for ($i = 0; $i < $days; $i++) {
            $this->_getApp()->update_quality();
        }
    }

    /**
     * Get GildedRose app
     * @return GildedRose
     */
    private function _getApp(): GildedRose
    {
        return $this->_app;
    }

    /**
     * Set GildedRose app
     * @param  array $items
     * @return GildedRoseTest
     */
    private function _setApp($items): GildedRoseTest
    {
        $this->_app = new GildedRose($items);
        return $this;
    }

}
