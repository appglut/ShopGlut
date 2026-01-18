jQuery(document).ready(function($) {
    
    // Simple refresh functionality
    $('.refresh-btn').on('click', function() {
        const $btn = $(this);
        const $icon = $btn.find('.dashicons');
        
        $icon.addClass('dashicons-update-spin');
        $btn.prop('disabled', true);
        
        // Simple page refresh after animation
        setTimeout(function() {
            window.location.reload();
        }, 800);
    });
    
    // Simple action link hover effects
    $('.action-link').on('mouseenter', function() {
        $(this).css('background-color', '#f6f7f7');
    }).on('mouseleave', function() {
        $(this).css('background-color', '#fff');
    });
    
    // Basic form submission handling
    $('form').on('submit', function() {
        const $submitBtn = $(this).find('input[type="submit"], button[type="submit"]');
        $submitBtn.prop('disabled', true).val('Please wait...');
    });
    
    // Simple confirmation for cleanup actions
    $('a[href*="cleanup"]').on('click', function(e) {
        if (!confirm('Are you sure you want to perform this cleanup action?')) {
            e.preventDefault();
        }
    });
    
    // Add simple spinning animation for refresh buttons
    $('<style>')
        .prop('type', 'text/css')
        .html('.dashicons-update-spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }')
        .appendTo('head');
});