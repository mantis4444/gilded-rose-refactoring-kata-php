<?php
/**
 * Enchanced brie type item class
 *
 * @category  mantis4444
 * @package   mantis4444/gilded-rose-php-refactored
 * @author    Mantas Koncius <info@mantaskoncius.com>
 */

class ItemEnhancedBrie extends ItemEnhanced {

    /**
     * Update item
     * @return void
     */
    public function update(): void {
        parent::update();

        // Increment quality
        if ($this->can_quality_be_incremented()) {
            $this->increment_quality();

            // Increment quality by 2 on negative sell in
            // But watch out for not to exceed max quality
            if ($this->is_sell_in_negative() &&
                $this->is_quality_under_max()) {
                $this->increment_quality();
            }
        }
    }

}
