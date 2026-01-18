<?php
namespace Shopglut\enhancements\wishlist;

trait WishlistTableCells {
    
    private function render_table_cell( $column, $product, $product_id, $wishlist_type, $list_name, $date_added, $attribute_taxonomies ) {
        // Access the table sort enhancements correctly
       

         if (is_wc_endpoint_url('my-wishlist') || is_account_page()) {
            
            $table_enhancements = isset($this->enhancements['wishlist-account-table-sort']) ? $this->enhancements['wishlist-account-table-sort'] : array();

        } else {

             $table_enhancements = isset( $this->enhancements['wishlist-table-sort'] ) ? $this->enhancements['wishlist-table-sort'] : array();   
        }
        
        
        switch ( $column ) {
            case 'wishlist-page-show-product-image':
                if ( isset( $table_enhancements['wishlist-page-show-product-image'] ) && $table_enhancements['wishlist-page-show-product-image'] == '1' ) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<td>' . $product->get_image( 'thumbnail' ) . '</td>';
                }
                break;
                
           case 'wishlist-page-show-product-name':
            if ( isset( $table_enhancements['wishlist-page-show-product-name'] ) && $table_enhancements['wishlist-page-show-product-name'] == '1' ) {
                $product_url = get_permalink( $product->get_id() );
                $product_title = $product->get_title();
                
                echo '<td>';
                echo '<a href="' . esc_url( $product_url ) . '" class="shopglut-product-link" target="_blank" rel="noopener">';
                echo esc_html( $product_title );
                echo '</a>';
                echo '</td>';
            }
            break;
                
            case 'wishlist-page-show-product-price':
                if ( isset( $table_enhancements['wishlist-page-show-product-price'] ) && $table_enhancements['wishlist-page-show-product-price'] == '1' ) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<td>' . wc_price( $product->get_price() ) . '</td>';
                }
                break;
                
            case 'wishlist-page-show-product-quantity':
                if ( isset( $table_enhancements['wishlist-page-show-product-quantity'] ) && $table_enhancements['wishlist-page-show-product-quantity'] == '1' ) {
                    echo '<td>
                        <input type="number" class="quantity" min="1" value="1" data-product-id="' . esc_attr( $product_id ) . '" />
                    </td>';
                }
                break;
                
            case 'wishlist-page-show-product-availability':
                if ( isset( $table_enhancements['wishlist-page-show-product-availability'] ) && $table_enhancements['wishlist-page-show-product-availability'] == '1' ) {
                    echo '<td>' . ( $product->is_in_stock() ? esc_html__( 'In Stock', 'shopglut' ) : esc_html__( 'Out of Stock', 'shopglut' ) ) . '</td>';
                }
                break;
                
            case 'wishlist-page-show-product-discount-info':
                if ( isset( $table_enhancements['wishlist-page-show-product-discount-info'] ) && $table_enhancements['wishlist-page-show-product-discount-info'] == '1' ) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<td class="discount-info">' . $this->get_product_discount_info( $product ) . '</td>';
                }
                break;
                
            case 'wishlist-page-show-product-review':
                if ( isset( $table_enhancements['wishlist-page-show-product-review'] ) && $table_enhancements['wishlist-page-show-product-review'] == '1' ) {
                    echo '<td class="product-rating">';
                    $rating = $product->get_average_rating();
                    if ( $rating > 0 ) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo wc_get_rating_html( $rating );
                    } else {
                        echo '-';
                    }
                    echo '</td>';
                }
                break;
                
            case 'wishlist-page-show-product-short-description':
                if ( isset( $table_enhancements['wishlist-page-show-product-short-description'] ) && $table_enhancements['wishlist-page-show-product-short-description'] == '1' ) {
                    echo '<td>' . esc_html( $product->get_short_description() ) . '</td>';
                }
                break;
                
            case 'wishlist-page-show-product-sku':
                if ( isset( $table_enhancements['wishlist-page-show-product-sku'] ) && $table_enhancements['wishlist-page-show-product-sku'] == '1' ) {
                    echo '<td>' . esc_html( $product->get_sku() ) . '</td>';
                }
                break;
                
            case 'wishlist-page-show-product-add-to-cart':
                if ( isset( $table_enhancements['wishlist-page-show-product-add-to-cart'] ) && $table_enhancements['wishlist-page-show-product-add-to-cart'] == '1' ) {
                    $this->render_add_to_cart_cell($product_id, $wishlist_type, $list_name);
                }
                break;
                
            case 'wishlist-page-show-product-checkout':
                if ( isset( $table_enhancements['wishlist-page-show-product-checkout'] ) && $table_enhancements['wishlist-page-show-product-checkout'] == '1' ) {
                    echo '<td>
                        <a href="#" class="checkout-link" data-product-id="' . esc_attr( $product_id ) . '" data-quantity="1">
                            ' . esc_html__( 'Checkout', 'shopglut' ) . '</a>
                    </td>';
                }
                break;
                
            case 'wishlist-page-show-product-date-added':
                if ( isset( $table_enhancements['wishlist-page-show-product-date-added'] ) && $table_enhancements['wishlist-page-show-product-date-added'] == '1' ) {
                    echo '<td class="date-added">' . esc_html( $date_added ) . '</td>';
                }
                break;

            case 'wishlist-page-show-product-urgency':
                if ( isset( $table_enhancements['wishlist-page-show-product-urgency'] ) && $table_enhancements['wishlist-page-show-product-urgency'] == '1' ) {
                    $urgency_message = $this->get_urgency_message($product);
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<td class="urgency-indicator">' . $urgency_message . '</td>';
                }
                break;
                    
            default:
                // Check if it's a dynamic attribute
                foreach ( $attribute_taxonomies as $attribute ) {
                    $attr_option_id = 'wishlist-page-show-' . $attribute->attribute_name;
                    if ( $column === $attr_option_id ) {
                        if ( isset( $table_enhancements[ $attr_option_id ] ) && $table_enhancements[ $attr_option_id ] == '1' ) {
                            $attribute_value = $product->get_attribute( $attribute->attribute_name );
                            echo '<td>' . esc_html( $attribute_value ) . '</td>';
                        }
                        break;
                    }
                }
                break;
        }
    }

    private function render_add_to_cart_cell($product_id, $wishlist_type, $list_name) {
        $remove_class = $this->enhancements['wishlist-remove-if-add-to-cart'] == '1' ? 'remove-after-add' : '';
        echo '<td>
            <button class="add-to-cart-btn ' . esc_attr( $remove_class ) . '" data-product-id="' . esc_attr( $product_id ) . '" data-quantity="1" data-wishlist-type="' . esc_attr( $wishlist_type ) . '" ' .
            ( $wishlist_type === 'list' ? 'data-list-name="' . esc_attr( $list_name ) . '"' : '' ) . '>' . esc_html__( 'Add to Cart', 'shopglut' ) . '</button>
        </td>';
    }

    private function get_urgency_message($product) {
        // Check for sale end date
        if ( $product->is_on_sale() ) {
            $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
            if ( $sale_end ) {
                $time_left = $sale_end - time();
                if ( $time_left > 0 && $time_left < 86400 ) { // Less than 24 hours
                    return '<span class="urgent-sale">🔥 Sale ends in ' . human_time_diff(time(), $sale_end) . '!</span>';
                }
            }
        }
        return '';
    }

    private function get_product_discount_info($product) {
        if (!$product->is_on_sale()) {
            return __('No discount', 'shopglut');
        }
        
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        
        if ($regular_price && $sale_price) {
            $discount_amount = $regular_price - $sale_price;
            $discount_percentage = round(($discount_amount / $regular_price) * 100);
            
            return sprintf( // translators: %1$s is the discount amount, %2$s is the discount percentage
                __('Save %1$s (%2$s%%)', 'shopglut'),
                wc_price($discount_amount),
                $discount_percentage
            );
        }
        
        return __('On Sale', 'shopglut');
    }
}