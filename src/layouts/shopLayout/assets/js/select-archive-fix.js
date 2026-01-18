jQuery(document).ready(function($) {
    // Fix for select_archive_page dropdown not showing options
    // Remove the filter-dis class that makes options invisible

    // Remove on page load
    setTimeout(function() {
        $(".agl-field-select_archive_page .chosen-results").removeClass("filter-dis");
    }, 500);

    // Remove when dropdown opens
    $(document).on('click', '.agl-field-select_archive_page .chosen-container', function() {
        setTimeout(function() {
            $(".agl-field-select_archive_page .chosen-results").removeClass("filter-dis");
        }, 50);
    });

    // Remove when Chosen updates
    $(document).on('chosen:updated', '.agl-field-select_archive_page select', function() {
        setTimeout(function() {
            $(".agl-field-select_archive_page .chosen-results").removeClass("filter-dis");
        }, 50);
    });
});
