<?php
/**
 * Enchanced conjured type item class
 *
 * @category  mantis4444
 * @package   mantis4444/gilded-rose-php-refactored
 * @author    Mantas Koncius <info@mantaskoncius.com>
 */

class ItemEnhancedConjured extends ItemEnhanced {

    /**
     * Update item
     * @return void
     */
    public function update(): void {
        parent::update();

        // Decrement quality
        if ($this->can_quality_be_decremented()) {
            $this->decrement_quality();

            // Double the decrement
            // But watch out for the negative quality
            if ($this->is_quality_over_min()) {
                $this->decrement_quality();
            }

            // Decrement quality by 4 on negative sell in
            // But watch out for the negative quality
            if ($this->is_sell_in_negative()) {
                for ($i = 0; $i < 2; $i++) {
                    if ($this->is_quality_over_min()) {
                        $this->decrement_quality();
                    }
                }
            }
        }
    }

}
