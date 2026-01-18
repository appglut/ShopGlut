jQuery(document).ready(function($) {
    
    // Sorting functionality - AJAX sorting without page reload
    $('.wishlist-sort-select').on('change', function() {
        const sortBy = $(this).val();
        const container = findActiveWishlistContainer($(this));
        if (container) {
            sortWishlistTable(sortBy, container);
        }
    });
    
    // Filtering functionality - AJAX filtering without page reload
    $('.wishlist-filter-select').on('change', function() {
        const filterBy = $(this).val();
        const container = findActiveWishlistContainer($(this));
        if (container) {
            filterWishlistTable(filterBy, container);
        }
    });
    
    // Select All functionality
    $('#select-all-checkbox').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.product-checkbox:visible').prop('checked', isChecked);
        updateBulkActionButtons();
    });
    
    // Individual checkbox change
    $(document).on('change', '.product-checkbox', function() {
        updateBulkActionButtons();
        updateSelectAllButton();
    });
    
    // NEW: Dropdown action apply button
    $('#shopglut-apply-action').on('click', function(e) {
        e.preventDefault();
        
        const selectedAction = $('#shopglut_product_actions').val();
        const selectedProducts = [];
        const selectedQuantities = [];
        
        $('.product-checkbox:checked').each(function() {
            const productId = $(this).val();
            const quantity = $(this).closest('tr').find('.quantity').val() || 1;
            selectedProducts.push(productId);
            selectedQuantities.push(quantity);
        });
        
        if (selectedProducts.length === 0) {
            alert('Please select products to perform action');
            return;
        }
        
        if (!selectedAction) {
            alert('Please select an action');
            return;
        }
        
        switch(selectedAction) {
            case 'add_to_cart_selected':
                bulkAddToCart(selectedProducts, selectedQuantities);
                break;
            case 'remove_selected':
                if (confirm('Are you sure you want to remove selected products from wishlist?')) {
                    bulkRemoveFromWishlist(selectedProducts);
                }
                break;
        }
    });
    
    // Add selected to cart (original button)
    $('#add-selected-to-cart').on('click', function() {
        const selectedProducts = [];
        const selectedQuantities = [];
        
        $('.product-checkbox:checked').each(function() {
            const productId = $(this).val();
            const quantity = $(this).closest('tr').find('.quantity').val() || 1;
            selectedProducts.push(productId);
            selectedQuantities.push(quantity);
        });
        
        if (selectedProducts.length === 0) {
            alert('Please select products to add to cart');
            return;
        }
        
        bulkAddToCart(selectedProducts, selectedQuantities);
    });
    
    // Add all to cart
    $('#add-all-to-cart').on('click', function() {
        if (confirm('Add all wishlist products to cart?')) {
            addAllToCart();
        }
    });
    
    // Print wishlist
    $('#print-wishlist').on('click', function() {
        printWishlist();
    });
    
    // Helper function to find the active wishlist container
    function findActiveWishlistContainer(element) {
        // For tabbed interface, find the active tab
        const activeTab = element.closest('.tab-content.active');
        if (activeTab.length > 0) {
            return activeTab;
        }
        
        // For single wishlist, find the container
        const singleContainer = element.closest('.shopglut-wishlist-table-container').parent();
        if (singleContainer.length > 0) {
            return singleContainer;
        }
        
        // Fallback to document body
        return $(document);
    }
    
    // Sorting function for wishlist table
    function sortWishlistTable(sortBy, container) {
        const table = container.find('.shopglut-wishlist-table');
        if (table.length === 0) return;
        
        const tbody = table.find('tbody');
        if (tbody.length === 0) return;
        
        // Get only product rows - DO NOT convert to array, keep as jQuery objects
        const productRows = tbody.find('tr.wishlist-product-row');
        if (productRows.length === 0) return;
        
        // Convert to array for sorting, but maintain jQuery objects
        const sortedRows = productRows.toArray().sort(function(a, b) {
            const rowA = $(a);
            const rowB = $(b);
            
            switch(sortBy) {
                case 'name':
                    let nameA = rowA.data('product-name');
                    if (!nameA) {
                        nameA = rowA.find('a.shopglut-product-link').text().trim();
                    }
                    if (!nameA) {
                        nameA = rowA.find('td:nth-child(3) a').text().trim();
                    }
                    if (!nameA) {
                        nameA = rowA.find('td:nth-child(3)').text().trim();
                    }
                    
                    let nameB = rowB.data('product-name');
                    if (!nameB) {
                        nameB = rowB.find('a.shopglut-product-link').text().trim();
                    }
                    if (!nameB) {
                        nameB = rowB.find('td:nth-child(3) a').text().trim();
                    }
                    if (!nameB) {
                        nameB = rowB.find('td:nth-child(3)').text().trim();
                    }
                    
                    return nameA.toLowerCase().localeCompare(nameB.toLowerCase());
                    
                case 'price_low':
                    const priceA = parseFloat(rowA.data('product-price')) || extractPrice(rowA) || 0;
                    const priceB = parseFloat(rowB.data('product-price')) || extractPrice(rowB) || 0;
                    return priceA - priceB;
                    
                case 'price_high':
                    const priceA2 = parseFloat(rowA.data('product-price')) || extractPrice(rowA) || 0;
                    const priceB2 = parseFloat(rowB.data('product-price')) || extractPrice(rowB) || 0;
                    return priceB2 - priceA2;
                    
                case 'availability':
                    const stockA = rowA.data('product-stock') === 'in_stock' ? 1 : 0;
                    const stockB = rowB.data('product-stock') === 'in_stock' ? 1 : 0;
                    return stockB - stockA; // In stock first
                    
                case 'date_added':
                default:
                    const dateA = parseInt(rowA.data('date-added')) || 0;
                    const dateB = parseInt(rowB.data('date-added')) || 0;
                    return dateB - dateA; // Newest first
            }
        });
        
        // Simple reordering: move each sorted row to its new position
        // This doesn't touch special rows at all
        const firstProductRow = tbody.find('tr.wishlist-product-row').first();
        if (firstProductRow.length > 0) {
            // Insert sorted rows before the first product row position
            $(sortedRows[0]).insertBefore(firstProductRow);
            for (let i = 1; i < sortedRows.length; i++) {
                $(sortedRows[i]).insertAfter($(sortedRows[i-1]));
            }
        }
    }
    
    // Helper function to extract price from DOM
    function extractPrice(row) {
        const priceText = row.find('.woocommerce-Price-amount').text();
        const price = priceText.replace(/[^0-9.]/g, '');
        return parseFloat(price) || 0;
    }
    
    // Filtering function for wishlist table
    function filterWishlistTable(filterBy, container) {
        const table = container.find('.shopglut-wishlist-table');
        if (table.length === 0) return;
        
        const tbody = table.find('tbody');
        const productRows = tbody.find('tr.wishlist-product-row');
        
        productRows.each(function() {
            const row = $(this);
            let show = true;
            
            switch(filterBy) {
                case 'in_stock':
                    show = row.data('product-stock') === 'in_stock';
                    break;
                case 'out_stock':
                    show = row.data('product-stock') === 'out_stock';
                    break;
                case 'on_sale':
                    show = row.data('product-sale') === 'on_sale';
                    break;
                case 'all':
                default:
                    show = true;
                    break;
            }
            
            if (show) {
                row.show();
            } else {
                row.hide();
                // Uncheck hidden items
                row.find('.product-checkbox').prop('checked', false);
            }
        });
        
        // Update bulk action buttons
        updateBulkActionButtons();
        updateSelectAllButton();
    }
    
    // Update bulk action buttons
    function updateBulkActionButtons() {
        const checkedCount = $('.product-checkbox:checked').length;
        $('#add-selected-to-cart').prop('disabled', checkedCount === 0);
        $('#shopglut-apply-action').prop('disabled', checkedCount === 0);
    }
    
    // Update select all button
    function updateSelectAllButton() {
        const visibleCheckboxes = $('.product-checkbox:visible');
        const checkedVisible = $('.product-checkbox:visible:checked');
        
        if (checkedVisible.length === visibleCheckboxes.length && visibleCheckboxes.length > 0) {
            $('#select-all-checkbox').prop('checked', true);
        } else {
            $('#select-all-checkbox').prop('checked', false);
        }
    }
    
    // Bulk add to cart function
    function bulkAddToCart(productIds, quantities) {
        $.ajax({
            url: wishlist_control_shopglut_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_bulk_add_to_cart',
                nonce: wishlist_control_shopglut_ajax.nonce,
                product_ids: productIds,
                quantities: quantities
            },
            beforeSend: function() {
                $('#add-selected-to-cart').text('Adding...').prop('disabled', true);
                $('#shopglut-apply-action').text('Adding...').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    // Reload wishlist if items were removed
                    if (response.data.added > 0) {
                        location.reload();
                    }
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while adding products to cart');
            },
            complete: function() {
                $('#add-selected-to-cart').text('Add Selected to Cart').prop('disabled', false);
                $('#shopglut-apply-action').text('Apply Action').prop('disabled', false);
            }
        });
    }
    
    // NEW: Bulk remove from wishlist function
    function bulkRemoveFromWishlist(productIds) {
        $.ajax({
            url: wishlist_control_shopglut_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_bulk_remove_from_wishlist',
                nonce: wishlist_control_shopglut_ajax.nonce,
                product_ids: productIds
            },
            beforeSend: function() {
                $('#shopglut-apply-action').text('Removing...').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while removing products from wishlist');
            },
            complete: function() {
                $('#shopglut-apply-action').text('Apply Action').prop('disabled', false);
            }
        });
    }
    
    // Add all to cart function
    function addAllToCart() {
        $.ajax({
            url: wishlist_control_shopglut_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_add_all_to_cart',
                nonce: wishlist_control_shopglut_ajax.nonce
            },
            beforeSend: function() {
                $('#add-all-to-cart').text('Adding...').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred while adding all products to cart');
            },
            complete: function() {
                $('#add-all-to-cart').text('Add All to Cart').prop('disabled', false);
            }
        });
    }
    
    // Print wishlist function
    function printWishlist() {
        const printWindow = window.open('', '_blank');
        const wishlistContent = $('.shopglut-wishlist-table').clone();
        
        // Remove checkboxes and action buttons for print
        wishlistContent.find('input[type="checkbox"]').remove();
        wishlistContent.find('.remove-btn').parent().remove();
        wishlistContent.find('th:first-child, td:first-child').remove();
        wishlistContent.find('th:last-child, td:last-child').remove();
        
        const printHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Wishlist</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; font-weight: bold; }
                    .product-rating { text-align: center; }
                    img { max-width: 50px; height: auto; }
                    @media print { 
                        body { margin: 0; }
                        table { font-size: 12px; }
                    }
                </style>
            </head>
            <body>
                <h1 style="text-align:center;">Wishlist</h1>
                <p>Generated on: ${new Date().toLocaleDateString()}</p>
                ${wishlistContent.prop('outerHTML')}
            </body>
            </html>
        `;
        
        printWindow.document.write(printHTML);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
    
});