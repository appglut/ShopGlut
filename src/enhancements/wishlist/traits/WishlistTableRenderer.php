<?php
namespace Shopglut\enhancements\wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WishlistTableRenderer {
    
    public function render_wishlist_table( $product_ids, $wishlist_type = 'main', $list_name = '' ) {
        do_action( 'shopglut_before_render_wishlist_table', $product_ids, $wishlist_type, $list_name );
        
        $this->internal_render_wishlist_table( $product_ids, $wishlist_type, $list_name );
        
        do_action( 'shopglut_after_render_wishlist_table', $product_ids, $wishlist_type, $list_name );
    }
    
    public static function trigger_wishlist_table_render( $product_ids, $wishlist_type = 'main', $list_name = '' ) {
        do_action( 'shopglut_render_wishlist_table', $product_ids, $wishlist_type, $list_name );
    }
    
    public function init_wishlist_table_hooks() {
        add_action( 'shopglut_render_wishlist_table', array( $this, 'handle_render_wishlist_table' ), 10, 3 );
    }
    
    public function handle_render_wishlist_table( $product_ids, $wishlist_type = 'main', $list_name = '' ) {
        $this->render_wishlist_table( $product_ids, $wishlist_type, $list_name );
    }
    
    private function internal_render_wishlist_table( $product_ids, $wishlist_type = 'main', $list_name = '' ) {
        $product_ids = array_filter( $product_ids );

        if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
            echo '<p>' . esc_html__( 'No wishlist products available.', 'shopglut' ) . '</p>';
            return;
        }

        // Get products with their data for sorting/filtering
        $products_data = $this->prepare_products_data($product_ids);
        
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        
         if (is_wc_endpoint_url('my-wishlist') || is_account_page()) {
            $table_sort_order = isset($this->enhancements['wishlist-account-table-sort']) ? $this->enhancements['wishlist-account-table-sort'] : array();
        } else {
            // On regular wishlist page
            $table_sort_order = isset($this->enhancements['wishlist-table-sort']) ? $this->enhancements['wishlist-table-sort'] : array();
        }
        
        // Default column order if no sorting is set
        $default_columns = $this->get_default_table_columns($attribute_taxonomies);
        
        // Use sorted order if available, otherwise use default
        $column_order = ! empty( $table_sort_order ) ? array_keys( $table_sort_order ) : $default_columns;
        
        echo '<div class="shopglut-wishlist-table-container">';

        echo '<table class="shopglut-wishlist-table" id="wishlist-products-table">';
        
        // Render table header
        $this->render_table_header($column_order, $attribute_taxonomies);
        
        echo '<tbody>';

        do_action( 'shopglut_before_table_body', $product_ids, $wishlist_type, $list_name );

        foreach ( $products_data as $product_data ) {
            $this->render_table_row($product_data, $column_order, $wishlist_type, $list_name, $attribute_taxonomies);
        }

                 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                 echo $this->render_wishlist_bulkAction();


        do_action( 'shopglut_after_table_body', $product_ids, $wishlist_type, $list_name );

        echo '</tbody>';
            

        echo '</table>';

           
        do_action( 'shopglut_after_table', $product_ids, $wishlist_type, $list_name );

        echo '</div>';

       
    }

    private function get_default_table_columns($attribute_taxonomies) {
        $default_columns = array(
            'wishlist-page-show-product-image',
            'wishlist-page-show-product-name',
            'wishlist-page-show-product-price',
            'wishlist-page-show-product-quantity',
            'wishlist-page-show-product-availability',
            'wishlist-page-show-product-discount-info',
            'wishlist-page-show-product-review',
            'wishlist-page-show-product-short-description',
            'wishlist-page-show-product-sku',
            'wishlist-page-show-product-add-to-cart',
            'wishlist-page-show-product-checkout',
            'wishlist-page-show-product-date-added',
            'wishlist-page-show-product-urgency'
        );
        
        // Add dynamic attribute columns to default
        foreach ( $attribute_taxonomies as $attribute ) {
            $default_columns[] = 'wishlist-page-show-' . $attribute->attribute_name;
        }
        
        return $default_columns;
    }

    private function render_table_header($column_order, $attribute_taxonomies) {
        echo '<thead><tr>';
        
        // Always show bulk select checkbox first
        echo '<th><input type="checkbox" id="select-all-checkbox" /></th>';
        
        // Render headers in sorted order
        foreach ( $column_order as $column ) {
            $this->render_table_header_cell( $column, $attribute_taxonomies );
        }
        
        // Always show remove button last
        echo '<th>' . esc_html__( 'Remove', 'shopglut' ) . '</th>';
        
        echo '</tr></thead>';
    }

    private function render_table_row($product_data, $column_order, $wishlist_type, $list_name, $attribute_taxonomies) {
        $product = $product_data['product'];
        $product_id = $product_data['id'];
        $date_added = $product_data['date_added'];
        
        if ( $product ) {
            echo '<tr data-product-id="' . esc_attr( $product_id ) . '" 
                     class="wishlist-product-row"
                     data-product-name="' . esc_attr( strtolower( $product->get_name() ) ) . '"
                     data-product-price="' . esc_attr( $product->get_price() ) . '"
                     data-product-stock="' . esc_attr( $product->is_in_stock() ? 'in_stock' : 'out_stock' ) . '"
                     data-product-sale="' . esc_attr( $product->is_on_sale() ? 'on_sale' : 'regular' ) . '"
                     data-date-added="' . esc_attr( strtotime( $date_added ) ) . '">';
            
            // Always show bulk select checkbox first
            echo '<td><input type="checkbox" class="product-checkbox" value="' . esc_attr( $product_id ) . '" /></td>';
            
            // Render cells in sorted order
            foreach ( $column_order as $column ) {
                $this->render_table_cell( $column, $product, $product_id, $wishlist_type, $list_name, $date_added, $attribute_taxonomies );
            }
            
            // Always show remove button last
            echo '<td><button class="remove-btn" data-wishlist-type="' . esc_attr( $wishlist_type ) . '" ' .
                'data-product-id="' . esc_attr( $product_id ) . '" ' .
                ( $wishlist_type === 'list' ? 'data-list-name="' . esc_attr( $list_name ) . '"' : '' ) . '>' .
                '<i class="fa fa-times"></i></button></td>';
            
            echo '</tr>';

        }
    }

    private function render_table_header_cell( $column, $attribute_taxonomies ) {
       
         if (is_wc_endpoint_url('my-wishlist') || is_account_page()) {
            
            $table_enhancements = isset($this->enhancements['wishlist-account-table-sort']) ? $this->enhancements['wishlist-account-table-sort'] : array();

        } else {
            
            $table_enhancements = isset($this->enhancements['wishlist-table-sort']) ? $this->enhancements['wishlist-table-sort'] : array();
        }
        
        switch ( $column ) {
            case 'wishlist-page-show-product-image':
                if ( isset( $table_enhancements['wishlist-page-show-product-image'] ) && $table_enhancements['wishlist-page-show-product-image'] == '1' ) {
                    echo '<th>' . esc_html__( 'Product Image', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-name':
                if ( isset( $table_enhancements['wishlist-page-show-product-name'] ) && $table_enhancements['wishlist-page-show-product-name'] == '1' ) {
                    echo '<th>' . esc_html__( 'Product Name', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-price':
                if ( isset( $table_enhancements['wishlist-page-show-product-price'] ) && $table_enhancements['wishlist-page-show-product-price'] == '1' ) {
                    echo '<th>' . esc_html__( 'Unit Price', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-quantity':
                if ( isset( $table_enhancements['wishlist-page-show-product-quantity'] ) && $table_enhancements['wishlist-page-show-product-quantity'] == '1' ) {
                    echo '<th>' . esc_html__( 'Quantity', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-availability':
                if ( isset( $table_enhancements['wishlist-page-show-product-availability'] ) && $table_enhancements['wishlist-page-show-product-availability'] == '1' ) {
                    echo '<th>' . esc_html__( 'Availability', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-discount-info':
                if ( isset( $table_enhancements['wishlist-page-show-product-discount-info'] ) && $table_enhancements['wishlist-page-show-product-discount-info'] == '1' ) {
                    echo '<th>' . esc_html__( 'Discount', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-review':
                if ( isset( $table_enhancements['wishlist-page-show-product-review'] ) && $table_enhancements['wishlist-page-show-product-review'] == '1' ) {
                    echo '<th>' . esc_html__( 'Review', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-short-description':
                if ( isset( $table_enhancements['wishlist-page-show-product-short-description'] ) && $table_enhancements['wishlist-page-show-product-short-description'] == '1' ) {
                    echo '<th>' . esc_html__( 'Description', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-sku':
                if ( isset( $table_enhancements['wishlist-page-show-product-sku'] ) && $table_enhancements['wishlist-page-show-product-sku'] == '1' ) {
                    echo '<th>' . esc_html__( 'SKU', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-add-to-cart':
                if ( isset( $table_enhancements['wishlist-page-show-product-add-to-cart'] ) && $table_enhancements['wishlist-page-show-product-add-to-cart'] == '1' ) {
                    echo '<th>' . esc_html__( 'Add to Cart', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-checkout':
                if ( isset( $table_enhancements['wishlist-page-show-product-checkout'] ) && $table_enhancements['wishlist-page-show-product-checkout'] == '1' ) {
                    echo '<th>' . esc_html__( 'Checkout', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-date-added':
                if ( isset( $table_enhancements['wishlist-page-show-product-date-added'] ) && $table_enhancements['wishlist-page-show-product-date-added'] == '1' ) {
                    echo '<th>' . esc_html__( 'Date Added', 'shopglut' ) . '</th>';
                }
                break;
                
            case 'wishlist-page-show-product-urgency':
                if ( isset( $table_enhancements['wishlist-page-show-product-urgency'] ) && $table_enhancements['wishlist-page-show-product-urgency'] == '1' ) {
                    echo '<th>' . esc_html__( 'Time Remaining', 'shopglut' ) . '</th>';
                }
                break;
                
            default:
                // Check if it's a dynamic attribute
                foreach ( $attribute_taxonomies as $attribute ) {
                    $attr_option_id = 'wishlist-page-show-' . $attribute->attribute_name;
                    if ( $column === $attr_option_id ) {
                        if ( isset( $table_enhancements[ $attr_option_id ] ) && $table_enhancements[ $attr_option_id ] == '1' ) {
                            echo '<th>' . esc_html( ucfirst( wc_attribute_label( $attribute->attribute_label ) ) ) . '</th>';
                        }
                        break;
                    }
                }
                break;
        }
    }
}