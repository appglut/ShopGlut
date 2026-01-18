jQuery(document).ready(function () {
  setTimeout(function () {
    jQuery("div#agshopglut_post_options").fadeIn("slow");
  }, 400);

  jQuery(".shopglut-admin .updated, .shopglut-admin .notice").each(function () {
    jQuery(this).attr("style", "display: none !important;");
  });

  jQuery(".shopglut__shortcode-selectable").click(function (e) {
    e.preventDefault();

    // Get the text content from the nested span
    var textToCopy = jQuery(this).find('.shopglut_lcopy-text').text().trim();

    // Copy to clipboard using modern API with fallback
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(textToCopy).then(function() {
        shopglutShowCopyNotification();
      }).catch(function() {
        shopglutCopyFallback(textToCopy);
      });
    } else {
      shopglutCopyFallback(textToCopy);
    }
  });

  // Copy notification function
  function shopglutShowCopyNotification() {
    // Create notification if it doesn't exist
    if (jQuery('.shopglut-copy-notification').length === 0) {
      jQuery('body').append('<div class="shopglut-copy-notification">Copied to clipboard!</div>');
    }

    var notification = jQuery('.shopglut-copy-notification');
    notification.addClass('show');

    setTimeout(function() {
      notification.removeClass('show');
    }, 2000);
  }

  // Fallback copy method
  function shopglutCopyFallback(text) {
    var tempInput = jQuery('<textarea>');
    jQuery('body').append(tempInput);
    tempInput.val(text).select();
    document.execCommand('copy');
    tempInput.remove();
    shopglutShowCopyNotification();
  }

  function ag_shopglut_copyToClipboard(element) {
    var jQuerytemp = jQuery("<input>");
    jQuery("body").append(jQuerytemp);
    jQuerytemp.val(jQuery(element).text()).select();
    document.execCommand("copy");
    jQuerytemp.remove();
  }
  function ag_shopglut_SelectText(element) {
    var r = document.createRange();
    var w = element.get(0);
    r.selectNodeContents(w);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(r);
  }

  function toggleSelectedLabels(container) {
    jQuery(container)
      .find("label")
      .click(function () {
        jQuery(this).siblings("label").removeClass("selected");
        jQuery(this).addClass("selected");
      });
  }

  // Call toggleSelectedLabels function for .slider-units and .agl-space-units containers
  toggleSelectedLabels(".slider-units");
  toggleSelectedLabels(".agl-space-units");

  jQuery("#shopg_live_preview .postbox-header").append(
    '<div class="responsive-display"><div class="desktop-display display-option" data-width="100%"><i class="fa-solid fa-desktop"></i></div><div class="tablet-display display-option" data-width="50%"><i class="fa-solid fa-tablet-screen-button"></i></div><div class="mobile-display display-option" data-width="30%"><i class="fa-solid fa-mobile-screen-button"></i></div></div>'
  );

  jQuery("#shopg_live_preview .responsive-display .display-option").click(function () {
    var width = jQuery(this).data("width");
    jQuery("#shopg_shop_layout_contents").css("width", width);

    // Adjust classes based on width
    jQuery("#shopg_shop_layout_contents").removeClass("width-100 width-50 width-30");
    if (width === "100%") {
      jQuery("#shopg_shop_layout_contents").addClass("width-100");
    } else if (width === "50%") {
      jQuery("#shopg_shop_layout_contents").addClass("width-50");
    } else if (width === "30%") {
      jQuery("#shopg_shop_layout_contents").addClass("width-30");
    }
  });

  jQuery("[data-depend-id='agl-responsive-desktop']").click(function () {
    jQuery("#shopg_shop_layout_contents").css("width", "100%").removeClass("width-50 width-30").addClass("width-100");
  });

  jQuery("[data-depend-id='agl-responsive-tablet']").click(function () {
    jQuery("#shopg_shop_layout_contents").css("width", "50%").removeClass("width-100 width-30").addClass("width-50");
  });

  jQuery("[data-depend-id='agl-responsive-mobile']").click(function () {
    jQuery("#shopg_shop_layout_contents").css("width", "30%").removeClass("width-100 width-50").addClass("width-30");
  });

  jQuery(".agl-field-slider .agl-fieldset .field-slider-wrap").each(function () {
    jQuery(this).siblings(".agl-field-slider .agl-fieldset .field-slider-wrap").not(":first").hide();
  });

  function handleSliderClick(selectTypeClass, sliderClass) {
    jQuery(selectTypeClass).on("click", function () {
      var sliderType = sliderClass.includes("row-gap") ? "row" : "column";
      var devices = ["desktop", "tablet", "mobile"];
      var active_device = sliderClass.split("_").pop();

      // Iterate over each fieldset
      jQuery(".agl-field-slider .agl-fieldset").each(function () {
        var jQueryfieldset = jQuery(this);
        devices.forEach(function (device) {
          if (device !== active_device) {
            jQueryfieldset.find(".slider-shopg-" + sliderType + "-gap-select-type_" + device).hide();
          }
        });
      });

      // Show the selected slider
      jQuery(".agl-field-slider .agl-fieldset").find(sliderClass).closest(".field-slider-wrap").show();
    });
  }

  // Define configurations for selectTypeClass and sliderClass
  var sliderConfigs = [
    {
      selectTypeClass: ".shopg-column-gap-select-type-desktop",
      sliderClass: ".slider-shopg-column-gap-select-type_desktop",
    },
    {
      selectTypeClass: ".shopg-column-gap-select-type-tablet",
      sliderClass: ".slider-shopg-column-gap-select-type_tablet",
    },
    {
      selectTypeClass: ".shopg-column-gap-select-type-mobile",
      sliderClass: ".slider-shopg-column-gap-select-type_mobile",
    },
    {
      selectTypeClass: ".shopg-row-gap-select-type-desktop",
      sliderClass: ".slider-shopg-row-gap-select-type_desktop",
    },
    {
      selectTypeClass: ".shopg-row-gap-select-type-tablet",
      sliderClass: ".slider-shopg-row-gap-select-type_table",
    },
    {
      selectTypeClass: ".shopg-row-gap-select-type-mobile",
      sliderClass: ".slider-shopg-row-gap-select-type_mobile",
    },
  ];

  // Call the function for each configuration
  sliderConfigs.forEach(function (config) {
    handleSliderClick(config.selectTypeClass, config.sliderClass);
  });

  jQuery(".agl-field-select .agl-fieldset select.active-device:gt(0)").hide();

  jQuery(".agl-res-devices label:first-child").addClass("selected");

  jQuery(".agl-res-devices label").click(function () {
    var labelClass = jQuery(this).attr("class");

    // Remove 'selected' class from all labels, add it to the clicked one
    jQuery(this).siblings("label").removeClass("selected");
    jQuery(this).addClass("selected");

    // Find the radio input within the clicked label and set it as checked
    var jQueryradioInput = jQuery(this).find('input[type="radio"]');
    jQueryradioInput.prop("checked", true);

    // Manually trigger the change event on the radio input
    jQueryradioInput.trigger("change");

    // Handle any additional logic needed after selecting the label
    showSelectType(labelClass);
  });

  function showSelectType(labelClass) {
    jQuery("select#" + labelClass).show();

    jQuery("#" + labelClass)
      .siblings("select")
      .hide();
  }

  //end responsive display for select type

  jQuery("label#lock-space-input input[type='checkbox']").click(function (event) {
    event.stopPropagation(); // Stop propagation to prevent double click
  });
  jQuery("label#lock-border-input input[type='checkbox']").click(function (event) {
    event.stopPropagation(); // Stop propagation to prevent double click
  });
  jQuery(".agl-res-devices label input[type='radio']").click(function (event) {
    event.stopPropagation(); // Stop propagation to prevent double click
  });

  function synchronizeInputs(mainInput, syncedInputs) {
    var mainValue = parseInt(mainInput.val());
    syncedInputs.each(function () {
      jQuery(this).val(mainValue);
    });
  }

  // Event handler for main input's increment/decrement within a set
  jQuery(".agl--inputs input[type='number']").on("input", function () {
    var mainInput = jQuery(this);
    var syncedInputs = mainInput.closest(".agl--inputs").find("input[type='number']").not(mainInput);
    if (!mainInput.closest(".agl--inputs").find(".lock-all").hasClass("locked")) {
      return; // Don't synchronize when unlocked
    }
    synchronizeInputs(mainInput, syncedInputs);
  });

  // Event handler for lock button within a set
  jQuery(".agl--inputs .lock-all").click(function () {
    var mainInput = jQuery(this).closest(".agl--inputs").find("input[type='number']");
    var syncedInputs = mainInput.closest(".agl--inputs").find("input[type='number']").not(mainInput);
    if (jQuery(this).hasClass("locked")) {
      // Unlock inputs
      jQuery(this).removeClass("locked dashicons-lock").addClass("unlocked dashicons-unlock");
      syncedInputs.prop("readonly", false);
    } else {
      // Lock inputs
      jQuery(this).removeClass("unlocked dashicons-unlock").addClass("locked dashicons-lock");
      syncedInputs.prop("readonly", true);
    }
  });

  jQuery(".agl-field .agl-field-space").each(function () {
    // Show the first .agl--inputs section
    jQuery(this).find(".agl--inputs").hide();
    jQuery(this).find(".agl--inputs").first().show();

    // Add 'selected' class to the first device label
    jQuery(this).find(".agl-res-devices label").first().addClass("selected");
  });

  // Handle click events on device labels
  jQuery(".agl-res-devices label").on("click", function () {
    var selectType = jQuery(this).attr("select-type");
    var parentField = jQuery(this).closest(".agl-field");

    // Remove 'selected' class from all device labels
    parentField.find(".agl-res-devices label").removeClass("selected");
    // Add 'selected' class to the clicked device label
    jQuery(this).addClass("selected");

    // Hide all input fields
    parentField.find(".agl--inputs").hide();
    // Show the selected input fields
    parentField.find("." + selectType).show();
  });

  jQuery("#shopg-shoplayout-container .agl-fieldset select").change(function (event) {
    // Check if the select element does not have the multiple attribute
    if (!jQuery(this).prop("multiple")) {
      // Prevent the default behavior of the select element
      jQuery(this).find("[selected]").removeAttr("selected");
      jQuery(this).find(":selected").attr("selected", "selected");
    }
  });

  jQuery('#shopg-shoplayout-container select[multiple="multiple"]').each(function () {
    // Select all options within the multiple select
    jQuery(this).find("option").prop("selected", true);
  });

  /* Shoplayouts Tabs */

  // Show the first tab content by default
  jQuery(".shopg-tab").first().addClass("active");
  jQuery(".shopg-tab-content").first().addClass("active");

  // Tab click event
  jQuery(".shopg-tab").on("click", function () {
    var tabId = jQuery(this).data("tab");

    // Remove active class from all tabs and hide all content
    jQuery(".shopg-tab").removeClass("active");
    jQuery(".shopg-tab-content").removeClass("active");

    // Add active class to the selected tab and show its content
    jQuery(this).addClass("active");
    jQuery("#" + tabId).addClass("active");
  });
});
