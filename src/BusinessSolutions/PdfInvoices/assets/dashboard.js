jQuery(document).ready(function($) {
    let analyticsChart = null;
    
    // Initialize dashboard
    initializeDashboard();
    
    function initializeDashboard() {
        loadDashboardStats();
        loadRecentDocuments();
        initializeChart();
        bindEvents();
        startAutoRefresh();
    }
    
    function loadDashboardStats() {
        $.ajax({
            url: shopglut_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_dashboard_stats',
                nonce: shopglut_dashboard.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateStatsDisplay(response.data);
                    updateChart(response.data.chart_data);
                } else {
                    showError('Failed to load dashboard statistics');
                }
            },
            error: function() {
                showError('Error loading dashboard statistics');
            }
        });
    }
    
    function updateStatsDisplay(data) {
        // Animate number changes
        $('[data-stat="total_invoices"]').animateNumber(data.total_invoices);
        $('[data-stat="invoices_change"]').html(data.invoices_change).addClass(getChangeClass(data.invoices_change));
        
        $('[data-stat="total_packing_slips"]').animateNumber(data.total_packing_slips);
        $('[data-stat="packing_slips_change"]').html(data.packing_slips_change).addClass(getChangeClass(data.packing_slips_change));
        
        $('[data-stat="total_ubl"]').animateNumber(data.total_ubl);
        $('[data-stat="ubl_change"]').html(data.ubl_change).addClass(getChangeClass(data.ubl_change));
        
        $('[data-stat="storage_used"]').animateText(data.storage_used);
        $('[data-stat="storage_change"]').html(data.storage_change);
        
        // Update performance metrics
        $('[data-metric="avg_generation_time"]').animateText(data.avg_generation_time);
        $('[data-metric="success_rate"]').animateText(data.success_rate);
        $('[data-metric="avg_file_size"]').animateText(data.avg_file_size);
        $('[data-metric="documents_today"]').animateText(data.documents_today);
    }
    
    function getChangeClass(changeValue) {
        if (changeValue.includes('+')) {
            return 'positive-change';
        } else if (changeValue.includes('-')) {
            return 'negative-change';
        }
        return 'neutral-change';
    }
    
    function loadRecentDocuments() {
        const $container = $('#recent-documents-list');
        
        $.ajax({
            url: shopglut_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_recent_documents',
                nonce: shopglut_dashboard.nonce
            },
            success: function(response) {
                if (response.success) {
                    displayRecentDocuments(response.data, $container);
                } else {
                    $container.html('<p class="no-data">No recent documents found</p>');
                }
            },
            error: function() {
                $container.html('<p class="error">Error loading recent documents</p>');
            }
        });
    }
    
    function displayRecentDocuments(documents, $container) {
        if (documents.length === 0) {
            $container.html('<p class="no-data">No recent documents found</p>');
            return;
        }
        
        let html = '';
        documents.forEach(function(doc) {
            html += `
                <div class="document-item">
                    <div class="document-info">
                        <h4>Order #${doc.order_number}</h4>
                        <p>
                            ${doc.customer_name} • ${doc.date} • ${doc.order_total}<br>
                            <span class="document-types">${doc.document_types}</span>
                        </p>
                    </div>
                    <div class="document-actions">
                        <button class="button regenerate-btn" data-order-id="${doc.order_id}" data-doc-type="invoice">
                            <span class="dashicons dashicons-update"></span>
                        </button>
                        <a href="${getDocumentUrl(doc.order_id, 'invoice')}" class="button download-btn" target="_blank">
                            <span class="dashicons dashicons-download"></span>
                        </a>
                    </div>
                </div>
            `;
        });
        
        $container.html(html);
    }
    
    function initializeChart() {
        const ctx = document.getElementById('analytics-chart');
        if (!ctx) return;
        
        // Initial empty chart
        analyticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        mode: 'index',
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });
    }
    
    function updateChart(chartData) {
        if (!analyticsChart || !chartData) return;
        
        analyticsChart.data = chartData;
        analyticsChart.update('active');
    }
    
    function bindEvents() {
        // Refresh recent documents
        $('#refresh-recent').on('click', function() {
            $(this).addClass('spinning');
            loadRecentDocuments();
            setTimeout(() => {
                $(this).removeClass('spinning');
            }, 1000);
        });
        
        // Analytics period change
        $('#analytics-period').on('change', function() {
            const period = $(this).val();
            loadAnalyticsData(period);
        });
        
        // Cleanup documents
        $('#cleanup-documents').on('click', function() {
            if (confirm(shopglut_dashboard.strings.confirm_cleanup)) {
                performCleanup();
            }
        });
        
        // Document regeneration
        $(document).on('click', '.regenerate-btn', function() {
            const $btn = $(this);
            const orderId = $btn.data('order-id');
            const docType = $btn.data('doc-type');
            
            if (confirm(shopglut_dashboard.strings.confirm_regenerate)) {
                regenerateDocument(orderId, docType, $btn);
            }
        });
        
        // Stat card hover effects
        $('.stat-card').on('mouseenter', function() {
            $(this).find('.stat-number').addClass('pulse');
        }).on('mouseleave', function() {
            $(this).find('.stat-number').removeClass('pulse');
        });
        
        // Panel minimize/maximize
        $('.panel-header').on('dblclick', function() {
            $(this).siblings('.panel-content').slideToggle(300);
            $(this).find('.panel-title').toggleClass('minimized');
        });
        
        // Auto-refresh toggle
        let autoRefreshEnabled = true;
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                autoRefreshEnabled = !autoRefreshEnabled;
                showNotification(
                    autoRefreshEnabled ? 'Auto-refresh enabled' : 'Auto-refresh disabled',
                    autoRefreshEnabled ? 'success' : 'info'
                );
            }
        });
    }
    
    function loadAnalyticsData(period) {
        $.ajax({
            url: shopglut_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_analytics_data',
                period: period,
                nonce: shopglut_dashboard.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateChart(response.data);
                }
            }
        });
    }
    
    function performCleanup() {
        const $btn = $('#cleanup-documents');
        const originalText = $btn.find('.button-text strong').text();
        
        $btn.prop('disabled', true).find('.button-text strong').text('Cleaning...');
        
        $.ajax({
            url: shopglut_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_cleanup_documents',
                nonce: shopglut_dashboard.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotification(
                        `Cleanup completed: ${response.data.deleted_files} files removed, ${response.data.freed_space} freed`,
                        'success'
                    );
                    loadDashboardStats(); // Refresh stats
                } else {
                    showError('Cleanup failed');
                }
            },
            error: function() {
                showError('Cleanup failed');
            },
            complete: function() {
                $btn.prop('disabled', false).find('.button-text strong').text(originalText);
            }
        });
    }
    
    function regenerateDocument(orderId, docType, $btn) {
        const $icon = $btn.find('.dashicons');
        $icon.addClass('spinning');
        $btn.prop('disabled', true);
        
        $.ajax({
            url: shopglut_dashboard.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_regenerate_document',
                order_id: orderId,
                document_type: docType,
                nonce: shopglut_dashboard.nonce
            },
            success: function(response) {
                if (response.success && response.data.success) {
                    showNotification(response.data.message, 'success');
                    $btn.closest('.document-item').addClass('highlight');
                    setTimeout(() => {
                        $btn.closest('.document-item').removeClass('highlight');
                    }, 2000);
                } else {
                    showError(response.data.message || 'Regeneration failed');
                }
            },
            error: function() {
                showError('Regeneration failed');
            },
            complete: function() {
                $icon.removeClass('spinning');
                $btn.prop('disabled', false);
            }
        });
    }
    
    function getDocumentUrl(orderId, docType) {
        const baseUrl = shopglut_dashboard.ajax_url;
        const action = `generate_pdf_${docType}`;
        return `${baseUrl}?action=${action}&order_id=${orderId}&_wpnonce=${shopglut_dashboard.nonce}`;
    }
    
    function startAutoRefresh() {
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                loadDashboardStats();
            }
        }, 30000); // Refresh every 30 seconds
        
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                loadRecentDocuments();
            }
        }, 60000); // Refresh every minute
    }
    
    // Helper functions
    $.fn.animateNumber = function(targetNumber) {
        const $element = this;
        const currentNumber = parseInt($element.text().replace(/,/g, '')) || 0;
        
        $element.prop('Counter', currentNumber).animate({
            Counter: parseInt(targetNumber.replace(/,/g, ''))
        }, {
            duration: 1000,
            easing: 'swing',
            step: function(now) {
                $element.text(Math.ceil(now).toLocaleString());
            }
        });
        
        return this;
    };
    
    $.fn.animateText = function(targetText) {
        const $element = this;
        $element.fadeOut(200, function() {
            $element.text(targetText).fadeIn(200);
        });
        return this;
    };
    
    function showNotification(message, type = 'info') {
        const $notification = $(`
            <div class="dashboard-notification ${type}">
                <span class="dashicons ${getNotificationIcon(type)}"></span>
                <span class="message">${message}</span>
                <button class="close-notification">
                    <span class="dashicons dashicons-no-alt"></span>
                </button>
            </div>
        `);
        
        $('body').append($notification);
        
        $notification.slideDown(300);
        
        setTimeout(() => {
            $notification.slideUp(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        $notification.find('.close-notification').on('click', function() {
            $notification.slideUp(300, function() {
                $(this).remove();
            });
        });
    }
    
    function getNotificationIcon(type) {
        switch (type) {
            case 'success': return 'dashicons-yes-alt';
            case 'error': return 'dashicons-warning';
            case 'warning': return 'dashicons-flag';
            default: return 'dashicons-info';
        }
    }
    
    function showError(message) {
        showNotification(message, 'error');
    }
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key) {
                case '1':
                    e.preventDefault();
                    $('[href*="shopglut_pdf_invoices_settings"]')[0]?.click();
                    break;
                case '2':
                    e.preventDefault();
                    $('[href*="edit.php?post_type=shop_order"]')[0]?.click();
                    break;
                case '3':
                    e.preventDefault();
                    $('#cleanup-documents').click();
                    break;
            }
        }
    });
    
    // Add CSS classes for animations
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .spinning {
                animation: spin 1s linear infinite !important;
            }
            
            .pulse {
                animation: pulse 1s ease-in-out infinite !important;
            }
            
            .highlight {
                background: #fef3c7 !important;
                border-color: #f59e0b !important;
                transition: all 0.3s ease !important;
            }
            
            .positive-change {
                color: #059669 !important;
                background: #ecfdf5 !important;
            }
            
            .negative-change {
                color: #dc2626 !important;
                background: #fef2f2 !important;
            }
            
            .neutral-change {
                color: #6b7280 !important;
                background: #f3f4f6 !important;
            }
            
            .dashboard-notification {
                position: fixed;
                top: 32px;
                right: 20px;
                background: white;
                border-left: 4px solid #3b82f6;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                border-radius: 0 8px 8px 0;
                padding: 16px 20px;
                display: none;
                z-index: 999999;
                max-width: 400px;
            }
            
            .dashboard-notification.success {
                border-left-color: #10b981;
            }
            
            .dashboard-notification.error {
                border-left-color: #ef4444;
            }
            
            .dashboard-notification.warning {
                border-left-color: #f59e0b;
            }
            
            .dashboard-notification .dashicons {
                margin-right: 8px;
                color: #6b7280;
            }
            
            .dashboard-notification .message {
                font-weight: 500;
                color: #374151;
            }
            
            .dashboard-notification .close-notification {
                background: none;
                border: none;
                position: absolute;
                top: 8px;
                right: 8px;
                color: #9ca3af;
                cursor: pointer;
                padding: 4px;
            }
            
            .no-data, .error {
                text-align: center;
                color: #6b7280;
                font-style: italic;
                padding: 40px 20px;
            }
            
            .error {
                color: #dc2626;
            }
            
            .panel-title.minimized::after {
                content: " (minimized)";
                font-size: 12px;
                color: #6b7280;
                font-weight: normal;
            }
        `)
        .appendTo('head');
    
    // Initialize tooltips for elements with title attributes
    $('[title]').each(function() {
        const title = $(this).attr('title');
        $(this).removeAttr('title');
        
        $(this).on('mouseenter', function(e) {
            const $tooltip = $(`<div class="custom-tooltip">${title}</div>`);
            $('body').append($tooltip);
            
            const offset = $(this).offset();
            $tooltip.css({
                position: 'absolute',
                top: offset.top - $tooltip.outerHeight() - 8,
                left: offset.left + ($(this).outerWidth() / 2) - ($tooltip.outerWidth() / 2),
                background: 'rgba(0,0,0,0.8)',
                color: 'white',
                padding: '6px 12px',
                borderRadius: '4px',
                fontSize: '12px',
                whiteSpace: 'nowrap',
                zIndex: 999999,
                pointerEvents: 'none'
            });
            
            setTimeout(() => $tooltip.fadeIn(200), 100);
        }).on('mouseleave', function() {
            $('.custom-tooltip').fadeOut(200, function() {
                $(this).remove();
            });
        });
    });
});