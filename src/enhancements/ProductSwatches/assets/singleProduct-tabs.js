/**
 * ShopGlut - Universal Product Tabs Handler
 *
 * This script handles WooCommerce product tabs across all templates
 * including in modals, AJAX loaded content, and regular page loads.
 *
 * Uses simple CSS class toggling (no inline styles) for maximum compatibility.
 *
 * Features:
 * - Works with dynamically loaded content (modals, AJAX)
 * - Uses event delegation for click handling
 * - Simple approach matching template1's working implementation
 * - MutationObserver for late-loaded content
 */

(function() {
    'use strict';

    // Unique namespace to prevent conflicts
    window.ShopGlutTabs = window.ShopGlutTabs || {};

    /**
     * Initialize tabs - ensure first tab is active
     * Follows template1's simple approach
     * Scoped to ShopGlut single product templates to avoid conflicts with WooCommerce admin tabs
     */
    function initTabs() {
        var tabsContainers = document.querySelectorAll('.shopglut-single-product .woocommerce-tabs, .shopglut-single-product .wc-tabs-wrapper');

        tabsContainers.forEach(function(container) {
            var tabs = container.querySelectorAll('.wc-tabs li, .tabs li');
            var panels = container.querySelectorAll('.woocommerce-Tabs-panel, .wc-tab');

            // Set first tab as active by default (if none are already active)
            var hasActiveTab = false;
            for (var i = 0; i < tabs.length; i++) {
                if (tabs[i].classList.contains('active')) {
                    hasActiveTab = true;
                    break;
                }
            }

            if (!hasActiveTab && tabs.length > 0) {
                tabs[0].classList.add('active');
            }

            // Ensure first panel is active
            var hasActivePanel = false;
            for (var i = 0; i < panels.length; i++) {
                if (panels[i].classList.contains('active')) {
                    hasActivePanel = true;
                    break;
                }
            }

            if (!hasActivePanel && panels.length > 0) {
                panels[0].classList.add('active');
            }
        });
    }

    /**
     * Initialize the tabs system
     * Follows template1's proven working approach
     */
    function initialize() {
        // Initialize immediately
        initTabs();

        // Re-initialize after DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initTabs();
            });
        }

        // Event delegation for tab clicks - exact same approach as template1
        // Scoped to ShopGlut single product templates to avoid conflicts with WooCommerce admin tabs
        document.addEventListener('click', function(e) {
            // Find if clicked on a tab link within ShopGlut templates
            var target = e.target;
            var link = target.matches('.shopglut-single-product .wc-tabs a, .shopglut-single-product .woocommerce-tabs .wc-tabs a, .shopglut-single-product .tabs a') ? target :
                       target.closest ? target.closest('.shopglut-single-product .wc-tabs a, .shopglut-single-product .woocommerce-tabs .wc-tabs a, .shopglut-single-product .tabs a') : null;

            if (!link) return;

            e.preventDefault();
            e.stopPropagation();

            // Find the tabs container
            var tabsContainer = link.closest('.woocommerce-tabs, .wc-tabs-wrapper');
            if (!tabsContainer) return;

            var tab = link.closest('li');
            var targetId = link.getAttribute('href');

            if (!targetId) return;

            // Find target panel - first in same container, then globally
            var targetPanel = tabsContainer.querySelector(targetId);
            if (!targetPanel) {
                targetPanel = document.getElementById(targetId.replace('#', ''));
            }

            if (!targetPanel) return;

            // Remove active classes from all tabs and panels in this container
            var allTabs = tabsContainer.querySelectorAll('.wc-tabs li, .tabs li');
            var allPanels = tabsContainer.querySelectorAll('.woocommerce-Tabs-panel, .wc-tab');

            for (var i = 0; i < allTabs.length; i++) {
                allTabs[i].classList.remove('active');
            }

            for (var i = 0; i < allPanels.length; i++) {
                allPanels[i].classList.remove('active');
            }

            // Add active classes to clicked tab and corresponding panel
            tab.classList.add('active');
            targetPanel.classList.add('active');
        }, true); // Use capture phase

        // MutationObserver for dynamically added content (modals, AJAX)
        // Scoped to ShopGlut single product templates to avoid conflicts
        if (window.MutationObserver) {
            var observer = new MutationObserver(function(mutations) {
                var shouldInit = false;

                for (var i = 0; i < mutations.length; i++) {
                    if (mutations[i].addedNodes.length) {
                        for (var j = 0; j < mutations[i].addedNodes.length; j++) {
                            var node = mutations[i].addedNodes[j];
                            if (node.nodeType === 1) { // Element node
                                // Only process nodes within ShopGlut single product templates
                                if (node.classList && node.classList.contains('shopglut-single-product')) {
                                    // Check if this node contains tabs
                                    if (node.querySelectorAll) {
                                        var nestedTabs = node.querySelectorAll('.woocommerce-tabs, .wc-tabs-wrapper');
                                        if (nestedTabs.length > 0) {
                                            shouldInit = true;
                                            break;
                                        }
                                    }
                                } else if (node.closest && node.closest('.shopglut-single-product')) {
                                    // Node is inside a ShopGlut template
                                    if (node.classList &&
                                        (node.classList.contains('woocommerce-tabs') ||
                                         node.classList.contains('wc-tabs-wrapper'))) {
                                        shouldInit = true;
                                        break;
                                    }
                                    // Check children
                                    if (node.querySelectorAll) {
                                        var nestedTabs = node.querySelectorAll('.woocommerce-tabs, .wc-tabs-wrapper');
                                        if (nestedTabs.length > 0) {
                                            shouldInit = true;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (shouldInit) break;
                }

                if (shouldInit) {
                    initTabs();
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        // Handle hash changes in URL
        // Scoped to ShopGlut single product templates to avoid conflicts
        function handleHashChange() {
            var hash = window.location.hash;
            if (hash && hash.indexOf('#tab-') === 0) {
                var targetTab = document.querySelector('.shopglut-single-product a[href="' + hash + '"]');
                if (targetTab) {
                    targetTab.click();
                }
            }
        }

        // Check hash on load
        handleHashChange();

        // Listen for hash changes
        window.addEventListener('hashchange', handleHashChange);

        // Expose public methods
        window.ShopGlutTabs.init = initTabs;
        window.ShopGlutTabs.reinit = initTabs;

        // Auto-reinit when custom event is fired (for AJAX content)
        document.addEventListener('shopglut_tabs_reinit', initTabs);
    }

    // Initialize immediately when script loads
    initialize();

})();
