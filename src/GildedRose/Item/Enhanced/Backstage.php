<?php
/**
 * Enchanced backstage type item class
 *
 * @category  mantis4444
 * @package   mantis4444/gilded-rose-php-refactored
 * @author    Mantas Koncius <info@mantaskoncius.com>
 */

class ItemEnhancedBackstage extends ItemEnhanced {

    /**
     * Update item
     * @return void
     */
    public function update(): void {
        parent::update();

        // Quality is 0 if sell in is negative
        if ($this->is_sell_in_negative()) {
            $this->set_quality(0);
            return;
        }

        // Increment quality
        if ($this->can_quality_be_incremented()) {
            $this->increment_quality();

            if ($this->get_sell_in() < 11 &&
                $this->is_quality_under_max()) {
                $this->increment_quality();

                if ($this->get_sell_in() < 6 &&
                    $this->is_quality_under_max()) {
                    $this->increment_quality();
                }
            }
        }
    }

}
