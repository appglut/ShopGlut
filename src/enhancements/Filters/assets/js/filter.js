jQuery(document).ready(function ($) {

  // Show loader on page load for filter editor
  // Wait for everything to fully load including images, styles, and scripts
  $(window).on('load', function() {
    // Add a small delay to ensure everything is rendered
    setTimeout(function() {
      $(".loader-overlay").css({"display": "none", "opacity": "0"});
     // $(".loader-container").hide();
    }, 500); // 500ms delay to ensure complete loading
  });

  // Fallback: Hide loader after maximum 10 seconds even if something doesn't load
  setTimeout(function() {
    $(".loader-overlay").css({"display": "none", "opacity": "0"});
   // $(".loader-container").hide();
  }, 10000);

  // Add smooth CSS transitions for accordion content
  function addSmoothTransitions() {
    var style = document.createElement('style');
    style.textContent = `
      .filter-title-accordion i {
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
      }
      .filter-title-accordion.collapsed i {
        transform: rotate(0deg);
      }
      .filter-title-accordion.expanded i {
        transform: rotate(180deg);
      }
      .filter-content.accordion-content {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
      }
      .filter-content.accordion-content.hidden {
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
        margin-top: 0;
        margin-bottom: 0;
      }
      .filter-content.accordion-content.active {
        max-height: 1000px;
        opacity: 1;
      }
    `;
    document.head.appendChild(style);
  }

  // Accordion functionality for filters - works for both frontend and backend
  function initFilterAccordion() {
    // Add smooth transitions
    addSmoothTransitions();

    // Initialize all accordion titles as expanded by default
    $(".filter-title-accordion").each(function() {
      var $this = $(this);
      var $content = $this.next(".filter-content.accordion-content");
      var expandIcon = $this.data("expand-icon") || "fa fa-plus";
      var closeIcon = $this.data("close-icon") || "fa fa-minus";

      // Set initial state - expanded
      $this.addClass("expanded");
      $content.addClass("active").removeClass("hidden");

      // Fix icon classes - ensure proper format
      var $icon = $this.find("i");
      var cleanedCloseIcon = closeIcon.replace(/\s+/g, ' ').trim();
      $icon.removeClass().addClass(cleanedCloseIcon);
    });

    // Accordion click handler
    $(".filter-title-accordion").off("click").on("click", function(e) {
      e.preventDefault();
      e.stopPropagation();
      var $this = $(this);
      var $content = $this.next(".filter-content.accordion-content");
      var $icon = $this.find("i");
      var expandIcon = $this.data("expand-icon") || "fa fa-plus";
      var closeIcon = $this.data("close-icon") || "fa fa-minus";

      // Clean up icon classes
      expandIcon = expandIcon.replace(/\s+/g, ' ').trim();
      closeIcon = closeIcon.replace(/\s+/g, ' ').trim();

      if ($content.hasClass("hidden")) {
        // Expand
        $content.removeClass("hidden").addClass("active");
        $this.removeClass("collapsed").addClass("expanded");
        $icon.removeClass().addClass(closeIcon);
      } else {
        // Collapse
        $content.removeClass("active").addClass("hidden");
        $this.removeClass("expanded").addClass("collapsed");
        $icon.removeClass().addClass(expandIcon);
      }
    });
  }

  // Initialize accordion when page loads
  initFilterAccordion();




});
