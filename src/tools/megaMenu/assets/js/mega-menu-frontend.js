/**
 * ShopGlut Mega Menu Frontend JavaScript
 */

(function($) {
    'use strict';

    var ShopGlutMegaMenu = {
        init: function() {
            if (!shopglutMegaMenuConfig.enabled) {
                return;
            }

            this.bindEvents();
            this.setupMenuStructure();
        },

        bindEvents: function() {
            var self = this;

            // Menu trigger events based on trigger method
            var triggerMethod = shopglutMegaMenuConfig.triggerMethod;

            if (triggerMethod === 'hover' || triggerMethod === 'both') {
                this.bindHoverEvents();
            }

            if (triggerMethod === 'click' || triggerMethod === 'both') {
                this.bindClickEvents();
            }

            // Escape key to close menus
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) { // Escape key
                    self.closeAllMenus();
                }
            });

            // Click outside to close menus
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.shopglut-mega-menu-trigger, .shopglut-mega-menu').length) {
                    self.closeAllMenus();
                }
            });

            // Handle window resize
            $(window).on('resize', function() {
                self.handleResize();
            });

            // Handle mobile overlay
            $('.shopglut-mega-menu-overlay').on('click', function() {
                self.closeAllMenus();
            });
        },

        bindHoverEvents: function() {
            var self = this;
            var menuItems = $('.shopglut-mega-menu-trigger');

            menuItems.on('mouseenter', function() {
                self.showMenu($(this));
            });

            menuItems.on('mouseleave', function() {
                var $trigger = $(this);
                setTimeout(function() {
                    if (!$trigger.is(':hover') && !$trigger.find('.shopglut-mega-menu').is(':hover')) {
                        self.hideMenu($trigger);
                    }
                }, 200);
            });

            // Prevent closing when hovering over the menu itself
            $('.shopglut-mega-menu').on('mouseenter', function(e) {
                e.stopPropagation();
            });

            $('.shopglut-mega-menu').on('mouseleave', function() {
                var $trigger = $(this).closest('.shopglut-mega-menu-trigger');
                setTimeout(function() {
                    if (!$trigger.is(':hover')) {
                        self.hideMenu($trigger);
                    }
                }, 200);
            });
        },

        bindClickEvents: function() {
            var self = this;

            $('.shopglut-mega-menu-trigger').on('click', function(e) {
                e.preventDefault();
                var $trigger = $(this);
                var $menu = $trigger.find('.shopglut-mega-menu');

                if ($menu.hasClass('active')) {
                    self.hideMenu($trigger);
                } else {
                    self.showMenu($trigger);
                }
            });
        },

        showMenu: function($trigger) {
            var self = this;
            var $menu = $trigger.find('.shopglut-mega-menu');

            // Close all other menus first
            this.closeAllMenus();

            // Add loading state if needed
            if ($menu.hasClass('mega-menu-loading')) {
                this.loadMenuContent($menu, function() {
                    self.activateMenu($menu, $trigger);
                });
            } else {
                this.activateMenu($menu, $trigger);
            }
        },

        activateMenu: function($menu, $trigger) {
            // Add active class to trigger
            $trigger.addClass('mega-menu-active');

            // Apply animation class
            $menu.addClass('animation-' + shopglutMegaMenuConfig.animation);

            // Show menu
            setTimeout(function() {
                $menu.addClass('active');
            }, 10);

            // Show mobile overlay
            if ($(window).width() <= 768) {
                $('.shopglut-mega-menu-overlay').addClass('active');
                $('body').addClass('mega-menu-open');
            }

            // Trigger custom event
            $(document).trigger('shopglut_mega_menu_opened', [$menu, $trigger]);
        },

        hideMenu: function($trigger) {
            var $menu = $trigger.find('.shopglut-mega-menu');

            // Remove active class
            $trigger.removeClass('mega-menu-active');
            $menu.removeClass('active');

            // Hide mobile overlay
            $('.shopglut-mega-menu-overlay').removeClass('active');
            $('body').removeClass('mega-menu-open');

            // Trigger custom event
            $(document).trigger('shopglut_mega_menu_closed', [$menu, $trigger]);
        },

        closeAllMenus: function() {
            var self = this;
            $('.shopglut-mega-menu-trigger').each(function() {
                self.hideMenu($(this));
            });
        },

        loadMenuContent: function($menu, callback) {
            var self = this;
            var templateId = shopglutMegaMenuConfig.selectedTemplate;

            if (!templateId) {
                $menu.removeClass('mega-menu-loading');
                callback();
                return;
            }

            // AJAX call to get menu content
            $.ajax({
                url: shopglutMegaMenuConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'shopglut_get_mega_menu_content',
                    template_id: templateId,
                    nonce: shopglutMegaMenuConfig.nonce
                },
                beforeSend: function() {
                    $menu.addClass('loading');
                },
                success: function(response) {
                    $menu.removeClass('loading mega-menu-loading');
                    if (response.success) {
                        $menu.html(response.data.html);
                        self.applyCustomSettings($menu);
                    } else {
                        $menu.html('<div class="mega-menu-error">' + shopglutMegaMenuConfig.strings.error + '</div>');
                    }
                    callback();
                },
                error: function() {
                    $menu.removeClass('loading mega-menu-loading');
                    $menu.html('<div class="mega-menu-error">' + shopglutMegaMenuConfig.strings.error + '</div>');
                    callback();
                }
            });
        },

        applyCustomSettings: function($menu) {
            var customSettings = shopglutMegaMenuConfig.customSettings[shopglutMegaMenuConfig.selectedTemplate] || {};

            // Apply custom colors
            if (customSettings.primary_color) {
                $menu.find('.mega-menu-category-title').css('color', customSettings.primary_color);
            }

            if (customSettings.background_color) {
                $menu.css('background-color', customSettings.background_color);
            }

            if (customSettings.text_color) {
                $menu.css('color', customSettings.text_color);
            }

            // Apply layout settings
            if (customSettings.columns) {
                $menu.removeClass('columns-2 columns-3 columns-4 columns-5')
                    .addClass('columns-' + customSettings.columns);
            }

            if (customSettings.menu_width) {
                $menu.css('width', customSettings.menu_width + 'px');
            }

            if (customSettings.border_radius) {
                $menu.css('border-radius', customSettings.border_radius);
            }
        },

        setupMenuStructure: function() {
            var self = this;
            var menuLocation = shopglutMegaMenuConfig.menuLocation;

            // Find the target menu
            var $targetMenu = this.findTargetMenu(menuLocation);

            if ($targetMenu.length === 0) {
                console.warn('ShopGlut Mega Menu: Target menu not found');
                return;
            }

            // Setup mega menu structure for menu items
            this.setupMenuItems($targetMenu);

            // Create mobile overlay if needed
            if ($('.shopglut-mega-menu-overlay').length === 0) {
                $('<div class="shopglut-mega-menu-overlay"></div>').insertAfter('body');
            }
        },

        findTargetMenu: function(location) {
            var $menu = $();

            switch (location) {
                case 'primary':
                    $menu = $('#primary-menu, .main-navigation, .site-navigation');
                    break;
                case 'secondary':
                    $menu = $('#secondary-menu, .secondary-navigation');
                    break;
                default:
                    // Try to find by ID or class
                    $menu = $('#' + location + ', .' + location);
                    break;
            }

            return $menu;
        },

        setupMenuItems: function($menu) {
            var self = this;

            // Find menu items that should have mega menus
            $menu.find('li').each(function() {
                var $menuItem = $(this);

                // Check if this menu item has subcategories or should have a mega menu
                if (self.shouldHaveMegaMenu($menuItem)) {
                    self.convertToMegaMenu($menuItem);
                }
            });
        },

        shouldHaveMegaMenu: function($menuItem) {
            // You can customize this logic based on your requirements
            // For example, check for specific classes, data attributes, or number of sub-items

            // Check if item has children (subcategories)
            if ($menuItem.children('ul').length > 0) {
                var $submenu = $menuItem.children('ul').first();
                var $subItems = $submenu.children('li');

                // Enable mega menu if there are enough sub-items
                if ($subItems.length >= 3) {
                    return true;
                }
            }

            // Check for mega menu class
            if ($menuItem.hasClass('mega-menu') || $menuItem.hasClass('shopglut-mega-menu')) {
                return true;
            }

            // Check for data attribute
            if ($menuItem.attr('data-mega-menu') === 'true') {
                return true;
            }

            return false;
        },

        convertToMegaMenu: function($menuItem) {
            // Add trigger class
            $menuItem.addClass('shopglut-mega-menu-trigger');

            // Get the existing submenu
            var $submenu = $menuItem.children('ul').first();
            var $subItems = $submenu.children('li');

            if ($subItems.length === 0) {
                return;
            }

            // Create mega menu structure
            var $megaMenu = $('<div class="shopglut-mega-menu grid-layout columns-4"></div>');

            // Apply custom settings if available
            var customSettings = shopglutMegaMenuConfig.customSettings[shopglutMegaMenuConfig.selectedTemplate] || {};
            if (customSettings.columns) {
                $megaMenu.removeClass('columns-2 columns-3 columns-4 columns-5')
                    .addClass('columns-' + customSettings.columns);
            }

            // Convert sub-items to mega menu structure
            this.convertSubItemsToMegaMenuStructure($subItems, $megaMenu);

            // Replace the original submenu with the mega menu
            $submenu.replaceWith($megaMenu);

            // Add loading state initially
            $megaMenu.addClass('mega-menu-loading');
        },

        convertSubItemsToMegaMenuStructure: function($subItems, $megaMenu) {
            var customSettings = shopglutMegaMenuConfig.customSettings[shopglutMegaMenuConfig.selectedTemplate] || {};

            $subItems.each(function() {
                var $item = $(this);
                var $link = $item.children('a').first();
                var $subSubmenu = $item.children('ul').first();
                var $subSubItems = $subSubmenu ? $subSubmenu.children('li') : $();

                // Create category structure
                var $category = $('<div class="mega-menu-category"></div>');

                // Add category image if enabled
                if (customSettings.show_images && $item.attr('data-category-image')) {
                    var $image = $('<div class="mega-menu-category-image">' +
                        '<img src="' + $item.attr('data-category-image') + '" alt="' + $link.text() + '">' +
                        '</div>');
                    $category.append($image);
                }

                // Add category title
                var $categoryTitle = $('<a href="' + $link.attr('href') + '" class="mega-menu-category-title">' +
                    $link.text() + '</a>');
                $category.append($categoryTitle);

                // Add subcategories if they exist
                if ($subSubItems.length > 0) {
                    var $subcategories = $('<ul class="mega-menu-subcategories"></ul>');

                    $subSubItems.each(function() {
                        var $subItem = $(this);
                        var $subLink = $subItem.children('a').first();

                        var $subCategory = $('<li>' +
                            '<a href="' + $subLink.attr('href') + '">' + $subLink.text() + '</a>' +
                            '</li>');

                        // Add product count if enabled
                        if (customSettings.show_product_count && $subItem.attr('data-product-count')) {
                            $subCategory.find('a').append(' <span class="mega-menu-product-count">(' + $subItem.attr('data-product-count') + ')</span>');
                        }

                        $subcategories.append($subCategory);
                    });

                    $category.append($subcategories);
                }

                $megaMenu.append($category);
            });
        },

        handleResize: function() {
            // Handle responsive behavior
            if ($(window).width() > 768) {
                // Desktop: close all menus
                this.closeAllMenus();
                $('.shopglut-mega-menu-overlay').removeClass('active');
                $('body').removeClass('mega-menu-open');
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ShopGlutMegaMenu.init();
    });

    // Make available globally
    window.ShopGlutMegaMenu = ShopGlutMegaMenu;

})(jQuery);