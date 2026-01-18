/**
 * ShopGlut Gallery Shortcode JavaScript
 *
 * @package Shopglut
 * @subpackage GalleryShortcode
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Gallery Constructor
    function ShopGlutGallery(element) {
        this.element = $(element);
        this.config = this.getConfig();
        this.init();
    }

    ShopGlutGallery.prototype = {
        init: function() {
            this.setupLayout();
            this.setupFilters();
            this.setupPagination();
            this.setupLazyLoading();
            this.setupAnimations();
            this.setupHoverEffects();
        },

        getConfig: function() {
            var configElement = $('#' + this.element.attr('id') + '-config');
            try {
                return JSON.parse(configElement.html());
            } catch (e) {
                console.warn('Invalid gallery configuration:', e);
                return {};
            }
        },

        setupLayout: function() {
            var layout = this.config.layout;
            var container = this.element.find('.shopglut-gallery-items');

            // Adjust grid based on responsive columns
            this.setupResponsiveLayout();

            // Layout-specific initialization
            if (layout === 'masonry') {
                this.setupMasonry();
            } else if (layout === 'isotope') {
                this.setupIsotope();
            }
        },

        setupResponsiveLayout: function() {
            var self = this;
            var config = this.config;

            function updateColumns() {
                var width = $(window).width();
                var columns = config.columns;

                if (width <= 768) {
                    columns = config.columnsMobile;
                } else if (width <= 1024) {
                    columns = config.columnsTablet;
                }

                self.element.find('.shopglut-gallery-items').css({
                    'grid-template-columns': 'repeat(' + columns + ', 1fr)'
                });
            }

            // Initial setup
            updateColumns();

            // Update on resize
            $(window).on('resize', this.debounce(updateColumns, 250));
        },

        setupMasonry: function() {
            if ($.fn.masonry) {
                this.element.find('.shopglut-gallery-items').masonry({
                    itemSelector: '.shopglut-gallery-item',
                    columnWidth: '.shopglut-gallery-item',
                    percentPosition: true,
                    gutter: this.getGutterSize()
                });
            }
        },

        setupIsotope: function() {
            if ($.fn.isotope) {
                var isoContainer = this.element.find('.shopglut-gallery-isotope');
                isoContainer.isotope({
                    itemSelector: '.shopglut-gallery-item',
                    layoutMode: 'fitRows',
                    stagger: 30,
                    transitionDuration: '0.4s',
                    hiddenStyle: {
                        opacity: 0,
                        transform: 'scale(0.8)'
                    },
                    visibleStyle: {
                        opacity: 1,
                        transform: 'scale(1)'
                    }
                });

                // Store isotope instance
                this.isotope = isoContainer.data('isotope');
            }
        },

        setupFilters: function() {
            if (!this.config.enableFilter) {
                return;
            }

            var self = this;
            var filterButtons = this.element.find('.shopglut-filter-btn');

            filterButtons.on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var filterValue = button.data('filter');

                // Update active button
                filterButtons.removeClass('active');
                button.addClass('active');

                // Apply filter
                if (self.isotope) {
                    // Isotope filtering
                    self.element.find('.shopglut-gallery-isotope').isotope({
                        filter: filterValue === '*' ? '*' : filterValue
                    });
                } else {
                    // Simple show/hide filtering
                    var items = self.element.find('.shopglut-gallery-item');
                    items.show();

                    if (filterValue !== '*') {
                        items.not('.' + filterValue).hide();
                    }
                }
            });
        },

        setupPagination: function() {
            var paginationType = this.config.paginationType;

            if (paginationType === 'load_more') {
                this.setupLoadMore();
            } else if (paginationType === 'yes') {
                this.setupStandardPagination();
            }
        },

        setupLoadMore: function() {
            var self = this;
            var loadMoreBtn = this.element.find('.shopglut-load-more-btn');

            if (loadMoreBtn.length === 0) {
                return;
            }

            loadMoreBtn.on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var page = parseInt(button.data('page'));
                var maxPages = parseInt(button.data('max-pages'));

                if (page > maxPages || button.hasClass('loading')) {
                    return;
                }

                // Show loading state
                button.addClass('loading').prop('disabled', true);
                button.find('.shopglut-loading-spinner').show();

                // Load more products via AJAX
                self.loadProducts(page, function(response) {
                    if (response.success && response.data.products.length > 0) {
                        // Append new products
                        self.appendProducts(response.data.products);

                        // Update button state
                        button.data('page', page + 1);

                        // Hide button if all products loaded
                        if (page >= maxPages) {
                            button.hide();
                        }
                    }

                    // Hide loading state
                    button.removeClass('loading').prop('disabled', false);
                    button.find('.shopglut-loading-spinner').hide();
                });
            });
        },

        setupStandardPagination: function() {
            var self = this;
            var paginationLinks = this.element.find('.shopglut-pagination-links a');

            paginationLinks.on('click', function(e) {
                e.preventDefault();

                var link = $(this);
                var href = link.attr('href');
                var pageMatch = href.match(/pg=(\d+)/);

                if (pageMatch) {
                    var page = parseInt(pageMatch[1]);
                    self.loadProducts(page, function(response) {
                        if (response.success) {
                            self.replaceProducts(response.data.products);
                            self.updatePaginationURL(page);

                            // Scroll to top of gallery
                            $('html, body').animate({
                                scrollTop: self.element.offset().top - 100
                            }, 500);
                        }
                    });
                }
            });
        },

        setupLazyLoading: function() {
            if (!this.config.lazyLoad) {
                return;
            }

            var self = this;

            function lazyLoadImages() {
                var lazyImages = self.element.find('img[data-src]');

                lazyImages.each(function() {
                    var img = $(this);
                    var src = img.data('src');

                    // Check if image is in viewport
                    if (self.isElementInViewport(img[0])) {
                        img.attr('src', src)
                           .removeAttr('data-src')
                           .addClass('loaded');
                    }
                });
            }

            // Initial check
            lazyLoadImages();

            // Check on scroll
            $(window).on('scroll', this.debounce(lazyLoadImages, 100));
            $(window).on('resize', this.debounce(lazyLoadImages, 100));
        },

        setupAnimations: function() {
            var animation = this.config.animation;

            if (animation === 'none') {
                return;
            }

            var self = this;
            var animated = false;

            function animateItems() {
                if (animated) {
                    return;
                }

                var items = self.element.find('.shopglut-gallery-item');
                var delay = 0;

                items.each(function(index) {
                    var item = $(this);

                    setTimeout(function() {
                        item.css('animation-delay', (index * 100) + 'ms');
                        item.addClass('animated');
                    }, delay);

                    delay += 50;
                });

                animated = true;
            }

            // Start animation when gallery is visible
            if (this.isElementInViewport(this.element[0])) {
                animateItems();
            } else {
                $(window).on('scroll', this.debounce(function() {
                    if (self.isElementInViewport(self.element[0])) {
                        animateItems();
                        $(window).off('scroll', arguments.callee);
                    }
                }, 100));
            }
        },

        setupHoverEffects: function() {
            var hoverEffect = this.config.hoverEffect;

            if (hoverEffect === 'none') {
                return;
            }

            // Hover effects are handled via CSS classes
            // This method can be used for JavaScript-based hover effects if needed
        },

        loadProducts: function(page, callback) {
            var self = this;

            $.ajax({
                url: shopglutGallery.ajaxurl,
                type: 'POST',
                data: {
                    action: 'gallery_load_products',
                    nonce: shopglutGallery.nonce,
                    page: page,
                    category: this.config.category,
                    orderby: this.config.orderby,
                    order: this.config.order,
                    gallery_id: this.config.galleryId
                },
                success: function(response) {
                    if (typeof callback === 'function') {
                        callback(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading products:', error);
                    if (typeof callback === 'function') {
                        callback({success: false, error: error});
                    }
                }
            });
        },

        appendProducts: function(products) {
            var container = this.element.find('.shopglut-gallery-items');
            var self = this;

            products.forEach(function(product) {
                var productHtml = self.createProductHtml(product);
                var productElement = $(productHtml);

                container.append(productElement);

                // Re-initialize isotope if available
                if (self.isotope) {
                    container.isotope('appended', productElement);
                }

                // Re-initialize masonry if available
                if ($.fn.masonry && container.data('masonry')) {
                    container.masonry('appended', productElement);
                }
            });

            // Trigger lazy loading
            this.setupLazyLoading();
        },

        replaceProducts: function(products) {
            var container = this.element.find('.shopglut-gallery-items');
            var self = this;

            // Clear existing products
            container.empty();

            // Add new products
            products.forEach(function(product) {
                var productHtml = self.createProductHtml(product);
                container.append(productHtml);
            });

            // Re-initialize layouts
            this.setupLayout();
            this.setupLazyLoading();
        },

        createProductHtml: function(product) {
            // This is a simplified version - in production, you'd want to
            // generate the same HTML structure as the template
            return '<div class="shopglut-gallery-item">' +
                   '<div class="shopglut-gallery-item-inner">' +
                   '<div class="shopglut-gallery-image-wrapper">' +
                   '<a href="' + product.permalink + '">' +
                   '<img src="' + product.image + '" alt="' + product.title + '">' +
                   '</a></div>' +
                   '<div class="shopglut-gallery-content">' +
                   '<h3 class="shopglut-gallery-title">' +
                   '<a href="' + product.permalink + '">' + product.title + '</a>' +
                   '</h3>' +
                   '<div class="shopglut-gallery-price">' + product.price + '</div>' +
                   '</div></div></div>';
        },

        updatePaginationURL: function(page) {
            if (history.pushState) {
                var newURL = window.location.href.split('?')[0] + '?pg=' + page;
                history.pushState({page: page}, '', newURL);
            }
        },

        getGutterSize: function() {
            var spacing = this.config.spacing;

            switch (spacing) {
                case 'none': return 0;
                case 'small': return 10;
                case 'medium': return 20;
                case 'large': return 30;
                default: return 20;
            }
        },

        isElementInViewport: function(el) {
            var rect = el.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        debounce: function(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
    };

    // Initialize galleries on document ready
    $(document).ready(function() {
        $('.shopglut-gallery').each(function() {
            new ShopGlutGallery(this);
        });
    });

    // Re-initialize galleries when new content is loaded dynamically
    $(document).on('shopglut_gallery_reinit', function() {
        $('.shopglut-gallery:not(.initialized)').each(function() {
            new ShopGlutGallery(this);
            $(this).addClass('initialized');
        });
    });

})(jQuery);