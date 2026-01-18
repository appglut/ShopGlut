/**
 * ShopGlut Filter Actions
 * Handles frontend filtering functionality for categories and tags
 */

(function($) {
    'use strict';

    // ShopGlut Filter Actions Object
    window.ShopGlutFilterActions = {

        /**
         * Initialize filter functionality
         */
        init: function() {
            this.bindEvents();
            this.initializeFilterState();
        },

        /**
         * Bind event handlers
         */
        bindEvents: function() {
            var self = this;

            // Apply Filter button click
            $(document).on('click', '.apply-filter-btn', function(e) {
                e.preventDefault();
                self.applyFilters();
            });

            // Reset Filter button click
            $(document).on('click', '.reset-filter-btn', function(e) {
                e.preventDefault();
                self.resetFilters();
            });

            // Filter checkbox/radio changes for real-time updates (optional)
            $(document).on('change', '.shopglut-filter-checkbox input', function(e) {
                self.updateFilterState();
            });

            // Handle pagination clicks
            $(document).on('click', '.shopglut-pagination a.page-numbers', function(e) {
                e.preventDefault();
                var pageUrl = $(this).attr('href');
                var pageNum = 1;

                // Extract page number from URL
                if (pageUrl) {
                    var match = pageUrl.match(/paged=(\d+)/);
                    if (match) {
                        pageNum = parseInt(match[1]);
                    }
                }

                self.loadPage(pageNum);
            });
        },

        /**
         * Initialize filter state
         */
        initializeFilterState: function() {
            this.filterState = {
                categories: [],
                tags: [],
                price_min: null,
                price_max: null
            };
        },

        /**
         * Update filter state based on current selections
         */
        updateFilterState: function() {
            var self = this;

            // Reset current state
            self.filterState.categories = [];
            self.filterState.tags = [];

            // Get selected categories - check both checked inputs and checked classes
            $('.shopglut-categories-hierarchical input[type="checkbox"]:checked, .shopglut-categories-hierarchical input[type="radio"]:checked').each(function() {
                var categoryId = $(this).val();
                if (categoryId && !isNaN(categoryId)) {
                    self.filterState.categories.push(parseInt(categoryId));
                }
            });

            // Also check for hidden inputs that might be created by the styling
            $('.shopglut-categories-hierarchical input[type="hidden"]:checked').each(function() {
                var categoryId = $(this).val();
                if (categoryId && !isNaN(categoryId)) {
                    if (!self.filterState.categories.includes(parseInt(categoryId))) {
                        self.filterState.categories.push(parseInt(categoryId));
                    }
                }
            });

            // Get selected tags - check both checked inputs and checked classes
            $('.shopglut-tags-cloud input[type="checkbox"]:checked, .shopglut-tags-cloud input[type="radio"]:checked').each(function() {
                var tagId = $(this).val();
                if (tagId && !isNaN(tagId)) {
                    self.filterState.tags.push(parseInt(tagId));
                }
            });

            // Also check for hidden inputs that might be created by the styling
            $('.shopglut-tags-cloud input[type="hidden"]:checked').each(function() {
                var tagId = $(this).val();
                if (tagId && !isNaN(tagId)) {
                    if (!self.filterState.tags.includes(parseInt(tagId))) {
                        self.filterState.tags.push(parseInt(tagId));
                    }
                }
            });

            // Fallback: Extract from checked classes if no inputs found
            if (self.filterState.categories.length === 0) {
                $('.shopglut-categories-hierarchical .shopglut-filter-checkbox.checked label').each(function() {
                    var forAttr = $(this).attr('for');
                    if (forAttr && forAttr.startsWith('cat-')) {
                        var categoryId = forAttr.replace('cat-', '');
                        if (categoryId && !isNaN(categoryId)) {
                            self.filterState.categories.push(parseInt(categoryId));
                        }
                    }
                });
            }

            if (self.filterState.tags.length === 0) {
                $('.shopglut-tags-cloud .shopglut-filter-checkbox.checked label').each(function() {
                    var forAttr = $(this).attr('for');
                    if (forAttr && forAttr.startsWith('tag-')) {
                        var tagId = forAttr.replace('tag-', '');
                        if (tagId && !isNaN(tagId)) {
                            self.filterState.tags.push(parseInt(tagId));
                        }
                    }
                });
            }

          },

        /**
         * Apply filters and reload products
         */
        applyFilters: function() {
            var self = this;

            // Update current filter state
            self.updateFilterState();

            // Build URL parameters
            var params = self.buildFilterParams();

            // If no filters selected, show message
            if (params.categories === undefined && params.tags === undefined) {
                alert('Please select at least one category or tag to filter.');
                return;
            }

            // Show loading state
            self.showLoading();

            // Get filter ID from page
            var filterId = $('.shopglut-filter-container').data('filter-id') || 0;

            // Perform AJAX request to filter products
            $.ajax({
                url: shopglut_filter_actions.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_filter_products',
                    filter_params: params,
                    filter_id: filterId,
                    page: 1,
                    nonce: shopglut_filter_actions.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.updateProductDisplay(response.data.products);
                        self.updatePagination(response.data);
                        // URL parameter update disabled - self.updateURL(params);
                    } else {
                        console.error('Filter error:', response.data);
                        self.showError('Failed to filter products');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    self.showError('Network error occurred');
                },
                complete: function() {
                    self.hideLoading();
                }
            });
        },

        /**
         * Reset all filters
         */
        resetFilters: function() {
            var self = this;

            // Clear all checkboxes and radios
            $('.shopglut-filter-checkbox input[type="checkbox"]').prop('checked', false);
            $('.shopglut-filter-checkbox input[type="radio"]').prop('checked', false);

            // Remove checked classes
            $('.shopglut-filter-checkbox').removeClass('checked');

            // Reset filter state
            self.initializeFilterState();

            // Reload products without filters
            self.loadDefaultProducts();
        },

        /**
         * Build filter parameters for AJAX request
         */
        buildFilterParams: function() {
            var params = {};

            if (this.filterState.categories.length > 0) {
                params.categories = this.filterState.categories;
            }

            if (this.filterState.tags.length > 0) {
                params.tags = this.filterState.tags;
            }

            return params;
        },

        /**
         * Update URL with filter parameters
         */
        updateURL: function(params) {
            if (window.history && window.history.pushState) {
                var url = new URL(window.location);

                // Update URL parameters
                if (params.categories) {
                    url.searchParams.set('cat', params.categories.join(','));
                } else {
                    url.searchParams.delete('cat');
                }

                if (params.tags) {
                    url.searchParams.set('tag', params.tags.join(','));
                } else {
                    url.searchParams.delete('tag');
                }

                // Update browser URL without page reload
                window.history.pushState({}, '', url);
            }
        },

        /**
         * Update product display with filtered results
         */
        updateProductDisplay: function(products) {
            // Find the products container - adjust selector based on your theme
            var $productsContainer = $('.products');

            if ($productsContainer.length) {
                $productsContainer.empty();
                $productsContainer.html(products);
            }

            // Trigger custom event for other scripts
            $(document).trigger('shopglut_products_updated', [products]);
        },

        /**
         * Update pagination display
         */
        updatePagination: function(data) {
            // Remove existing pagination
            $('.shopglut-pagination').remove();

            // Add new pagination if exists
            if (data.pagination) {
                $('.products').after(data.pagination);
            }

            // Store current filter state for pagination
            this.currentFilterData = data;
        },

        /**
         * Load default products (no filters)
         */
        loadDefaultProducts: function() {
            var self = this;

            self.showLoading();

            // Get filter ID from page
            var filterId = $('.shopglut-filter-container').data('filter-id') || 0;

            $.ajax({
                url: shopglut_filter_actions.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_filter_products',
                    filter_params: {},
                    filter_id: filterId,
                    page: 1,
                    nonce: shopglut_filter_actions.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.updateProductDisplay(response.data.products);
                        self.updatePagination(response.data);
                        // URL parameter clearing disabled
                        // if (window.history && window.history.pushState) {
                        //     var url = new URL(window.location);
                        //     url.searchParams.delete('cat');
                        //     url.searchParams.delete('tag');
                        //     window.history.pushState({}, '', url);
                        // }
                    } else {
                        console.error('Load error:', response.data);
                        self.showError('Failed to load products');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    self.showError('Network error occurred');
                },
                complete: function() {
                    self.hideLoading();
                }
            });
        },

        /**
         * Load a specific page with current filters
         */
        loadPage: function(pageNum) {
            var self = this;

            // Show loading
            self.showLoading();

            // Get current filter state
            var params = self.buildFilterParams();
            var filterId = $('.shopglut-filter-container').data('filter-id') || 0;

            // Perform AJAX request for paginated results
            $.ajax({
                url: shopglut_filter_actions.ajax_url,
                type: 'POST',
                data: {
                    action: 'shopglut_filter_products',
                    filter_params: params,
                    filter_id: filterId,
                    page: pageNum,
                    nonce: shopglut_filter_actions.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.updateProductDisplay(response.data.products);
                        self.updatePagination(response.data);
                    } else {
                        console.error('Pagination error:', response.data);
                        self.showError('Failed to load page');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    self.showError('Network error occurred');
                },
                complete: function() {
                    self.hideLoading();
                }
            });
        },

        /**
         * Show loading state
         */
        showLoading: function() {
            $('.products').addClass('loading').append('<div class="shopglut-loading-overlay"><div class="spinner"></div></div>');
        },

        /**
         * Hide loading state
         */
        hideLoading: function() {
            $('.products').removeClass('loading');
            $('.shopglut-loading-overlay').remove();
        },

        /**
         * Show error message
         */
        showError: function(message) {
            // You can implement a toast notification or alert here
            console.error('Filter error:', message);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        if (typeof shopglut_filter_actions !== 'undefined') {
            ShopGlutFilterActions.init();
        } else {
            console.error('shopglut_filter_actions object not found');
        }
    });

})(jQuery);