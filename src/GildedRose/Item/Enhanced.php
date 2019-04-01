<?php
/**
 * Enhanced item with more features
 *
 * @category  mantis4444
 * @package   mantis4444/gilded-rose-php-refactored
 * @author    Mantas Koncius <info@mantaskoncius.com>
 */

class ItemEnhanced extends Item {

    const TYPE_BACKSTAGE = 'Backstage passes to a TAFKAL80ETC concert';
    const TYPE_BRIE      = 'Aged Brie';
    const TYPE_CONJURED  = 'Conjured Mana Cake';
    const TYPE_SULFURAS  = 'Sulfuras, Hand of Ragnaros';
    const TYPE_SULFURAS_QUALITY = 80;
    const QUALITY_MAX = 50;
    const QUALITY_MIN =  0;

    /**
     * Update item
     * @return ItemEnhanced
     */
    public function update(): void {
        $this->decrement_sell_in();
    }

    /**
     * Decrement sell in by days
     * @param  int $days     | Default: 1
     * @return ItemEnhanced
     */
    public function decrement_sell_in(int $days = 1): ItemEnhanced {
        $sell_in = $this->get_sell_in() - $days;
        $this->set_sell_in($sell_in);
        return $this;
    }

    /**
     * Increment quality by
     * @param  int $incrBy   | Default: 1
     * @return ItemEnhanced
     */
    public function increment_quality(int $incrBy = 1): ItemEnhanced {
        $quality = $this->get_quality() + $incrBy;
        $this->set_quality($quality);
        return $this;
    }

    /**
     * Decrement quality by
     * @param  int $decrBy   | Default: 1
     * @return ItemEnhanced
     */
    public function decrement_quality(int $decrBy = 1): ItemEnhanced {
        $this->increment_quality(-$decrBy);
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function get_name(): string {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     * @return ItemEnhanced
     */
    public function set_name(string $name): ItemEnhanced {
        $this->name = $name;
        return $this;
    }

    /**
     * Get sell in
     * @return int
     */
    public function get_sell_in(): int {
        return $this->sell_in;
    }

    /**
     * Set sell in
     * @param int $sell_in
     * @return ItemEnhanced
     */
    public function set_sell_in(int $sell_in): ItemEnhanced {
        $this->sell_in = $sell_in;
        return $this;
    }

    /**
     * Get quality
     * @return int
     */
    public function get_quality(): int {
        return $this->quality;
    }

    /**
     * Set quality
     * @param int $quality
     * @return ItemEnhanced
     */
    public function set_quality(int $quality): ItemEnhanced {
        $this->quality = $quality;
        return $this;
    }

    /**
     * Check if sell in is negative
     * @return boolean
     */
    protected function is_sell_in_negative(): bool {
        return ($this->get_sell_in() < self::QUALITY_MIN);
    }

    /**
     * Check if quality is under maximum value
     * @param  int $qualityMinOffset  | Default: 0
     * @return boolean
     */
    protected function is_quality_under_max(int $qualityMaxOffset = 0): bool {
        return ($this->get_quality() < self::QUALITY_MAX + $qualityMaxOffset);
    }

    /**
     * Check if quality is over min value
     * @param  int $qualityMinOffset  | Default: 0
     * @return boolean
     */
    protected function is_quality_over_min(int $qualityMinOffset = 0): bool {
        return ($this->get_quality() > self::QUALITY_MIN + $qualityMinOffset);
    }

    /**
     * Check if quality is under or equal max value
     * @return boolean
     */
    protected function is_quality_under_or_equal_max(): bool {
        return $this->is_quality_under_max(1);
    }

    /**
     * Check if quality os over or equal min value
     * @return boolean
     */
    protected function is_quality_over_or_equal_min(): bool {
        return $this->is_quality_over_min(-1);
    }

    /**
     * Can quality be incremented
     * @return boolean
     */
    protected function can_quality_be_incremented(): bool {
        return
            $this->is_quality_over_or_equal_min() &&
            $this->is_quality_under_max();
    }

    /**
     * Can quality be decremented
     * @return boolean
     */
    protected function can_quality_be_decremented(): bool {
        return
            $this->is_quality_over_min() &&
            $this->is_quality_under_or_equal_max();
    }

}
