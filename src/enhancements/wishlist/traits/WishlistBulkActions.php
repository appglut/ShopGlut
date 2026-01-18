<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistBulkActions {
    
    private function render_wishlist_bulkAction() {

        ob_start(); ?>

        <!-- Bulk Actions Table Row -->
        <tr class="shopglut-wishlist-bulk-actions-row">
            <td colspan="9" class="shopglut-wishlist-bulk-actions-container">
                <div class="shopglut-wishlist-bulk-actions">
                    <div class="shopglut-to-left look_in">
                        <div class="shopglut-input-group shopglut-no-full">
                            <input type="hidden" name="lists_per_page" value="10" id="shopglut_lists_per_page">
                            <select name="product_actions" id="shopglut_product_actions" class="shopglut-break-input-filed form-control">
                                <option value="" selected="selected"><?php echo esc_html__('Actions', 'shopglut'); ?></option>
                                <option value="add_to_cart_selected"><?php echo esc_html__('Add to Cart', 'shopglut'); ?></option>
                                <option value="remove_selected"><?php echo esc_html__('Remove', 'shopglut'); ?></option>
                            </select>
                            <span class="shopglut-input-group-btn">
                                <button type="submit" class="button" name="shopglut-action-product_apply" value="product_apply" title="<?php echo esc_attr__('Apply Action', 'shopglut'); ?>" id="shopglut-apply-action">
                                    <?php echo esc_html__('Apply', 'shopglut'); ?> <span class="shopglut-mobile"><?php echo esc_html__('Action', 'shopglut'); ?></span>
                                </button>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Original buttons (you can keep these as alternatives or remove them) -->
                    <div class="original-bulk-buttons" style="margin-top: 10px;">
                        <button class="btn-add-selected-cart" id="add-selected-to-cart" disabled><?php echo esc_html__('Add Selected to Cart', 'shopglut'); ?></button>
                        <button class="btn-add-all-cart" id="add-all-to-cart"><?php echo esc_html__('Add All to Cart', 'shopglut'); ?></button>
                    </div>
                </div>
            </td>
        </tr>

        <?php 

        return ob_get_clean();
    }
}