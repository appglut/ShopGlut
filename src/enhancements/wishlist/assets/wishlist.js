/**
 * ShopGlut Wishlist - Free Version JavaScript
 * Core wishlist functionality
 */
jQuery(document).ready(function ($) {
  // Initialize core functionality
  mergeGuestWishlist();

  // Function to get or create the guest user ID
  function getOrCreateGuestId() {
    // Retrieve the guest_user_id from cookies
    const guestIdCookie = document.cookie.split("; ").find((row) => row.startsWith("shopglutw_guest_user_id="));

    if (guestIdCookie) {
      return guestIdCookie.split("=")[1];
    } else {
      return "";
    }
  }

  // Basic add to cart functionality
  $("body").on("click", ".shopglut-wishlist-table .add-to-cart-btn", function () {
    const $button = $(this); // Store reference to the clicked button
    const originalText = $button.text(); // Store original button text
    const productId = $button.data("product-id");
    const quantity = $button.closest("tr").find(".quantity").val() || 1;
    var wishlist_type = $button.data("wishlist-type");
    var list_name = $button.data("list-name") || null;
    const shouldRemove = $button.hasClass("remove-after-add");

    // Disable button to prevent multiple clicks
    $button.prop('disabled', true).text('Adding...');

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_wishlist_add_to_cart",
        product_id: productId,
        quantity: quantity,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          showNotification(
            "<div class='success-added'><i class='fa fa-check-circle'></i> <span>Product added to Cart<span/></div>"
          );
          
          // Change button text to "Added" and keep it disabled
          $button.text('Added').removeClass('add-to-cart-btn').addClass('added-to-cart');
          
          if (shouldRemove) {
            removeItemFromWishlist(productId, wishlist_type, list_name);
          }
        } else {
          // Restore original state on error
          $button.prop('disabled', false).text(originalText);
          alert(response.data || "Failed to add product to cart.");
        }
      },
      error: function (xhr, status, error) {
        // Restore original state on error
        $button.prop('disabled', false).text(originalText);
        console.error("Add to Cart request failed:", xhr.responseText || error);
        alert("Failed to add product to cart. Please try again.");
      },
    });
  });


  // Basic remove from wishlist functionality
  $("body").on("click", ".shopglut-wishlist-table .remove-btn", function () {
    var product_id = $(this).data("product-id");
    var wishlist_type = $(this).data("wishlist-type");
    var list_name = $(this).data("list-name") || null;

    // Show a confirmation dialog
    var confirmed = confirm("Are you sure you want to delete this item from your wishlist?");
    if (!confirmed) {
      return;
    }

    // Proceed with the AJAX request if confirmed
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_remove_from_wishlist",
        product_id: product_id,
        wishlist_type: wishlist_type,
        list_name: list_name,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          showNotification(
            "<div class='wishlist-removed'><i class='fa fa-times-circle'></i> Product removed from Wishlist</div>"
          );
          loadWishlistContent();
        } else {
          alert(response.data || "Failed to remove product from wishlist.");
        }
      },
      error: function () {
        alert("An error occurred. Please try again.");
      },
    });
  });

  // Function to remove item from wishlist
  function removeItemFromWishlist(productId, wishlist_type, list_name) {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_remove_from_wishlist",
        product_id: productId,
        wishlist_type: wishlist_type,
        list_name: list_name,
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          showNotification(
            "<div class='wishlist-removed'><i class='fa fa-times-circle'></i> Product removed from wishlist</div>"
          );
          loadWishlistContent();
        } else {
          alert(response.data || "Failed to remove product from wishlist.");
        }
      },
      error: function () {
        alert("An error occurred. Please try again.");
      },
    });
  }

  // Function to reload the wishlist content
 function loadWishlistContent() {
    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "shopglut_load_wishlist_content",
        nonce: ajax_data.nonce,
      },
      success: function (response) {
        if (response.success) {
          // Find the content inside the response and extract only the inner HTML
          var $responseContent = $(response.data.content);
          var innerContent = $responseContent.html() || response.data.content;
          
          // Replace only the content inside the container
          $(".shoglut-wishlist-tabs").html(innerContent);
          
          $(document).trigger('wishlist_content_reloaded');
        } else {
          alert("Failed to reload wishlist content.");
        }
      },
      error: function () {
        alert("An error occurred while reloading the wishlist.");
      },
    });
}
  // Basic notification function
  function showNotification(message) {
    const $notification = $("#shopglut-wishlist-notification");

    if ($notification.length === 0) {
      // Create notification div if it doesn't exist
      $('body').append('<div id="shopglut-wishlist-notification" style="display: none;"></div>');
    }

    // Clear any existing content and show notification
    $("#shopglut-wishlist-notification")
      .stop(true, true) // Stop any ongoing animations
      .html(message)
      .css('display', 'block') // Force display block
      .fadeIn(500)
      .delay(4000) // Show for 4 seconds instead of 2
      .fadeOut(800);
  }

  // Function to update counter display (shared with wishlist-counter.js logic)
  function updateCounterDisplay(count, animation) {
    $('.counter-bubble').each(function() {
      $(this)
        .text(count)
        .addClass(animation);
      
      // Remove animation class after animation completes
      setTimeout(() => {
        $(this).removeClass(animation);
      }, 500);
    });
  }
  
  // Simple repaint function (lightweight)
  function forceElementRepaint(element) {
    if (!element) return;
    
    // Simple, single method to force repaint
    element.style.transform = 'translateZ(0)';
    element.offsetHeight; // Trigger reflow
  }

  // Guest wishlist merge functionality
  function mergeGuestWishlist() {
    let guestId = getOrCreateGuestId();

    if (guestId && ajax_data.should_merge_wishlist) {
      $.ajax({
        url: ajax_data.ajax_url,
        type: "POST",
        data: {
          action: "shopglut_merge_guest_wishlist",
          guest_id: guestId,
          nonce: ajax_data.nonce,
        },
        success: function (response) {
          // Wishlist merged successfully
        },
        error: function (xhr, status, error) {
          // Silently fail
        },
      });
    }
  }

  // Core wishlist toggle functionality
  $(document).on(
    "click",
    ".shopglut_wishlist .not-shopgw-added, .shopglut_wishlist .shopgw-added , .shopglut_wishlist .already-added",
    function (e) {
      const $button = $(this);
      const isAdded = $button.hasClass("shopgw-added");
      let guestId = getOrCreateGuestId();

      e.preventDefault();

      $.ajax({
        url: ajax_data.ajax_url,
        type: "POST",
        data: {
          action: "shopglut_toggle_wishlist",
          product_id: $button.data("product-id"),
          is_added: isAdded ? 1 : 0,
          shog_wishlist_guest_id: guestId,
          post_type: ajax_data.post_type,
          nonce: ajax_data.nonce,
        },
        success: function (response) {
          if (response.success) {
            const performToggle = response.data.perform_toggle;

            if (performToggle === true) {
              if (isAdded) {
                $button.removeClass("shopgw-added").addClass("not-shopgw-added");
                $button.find("i").attr("class", response.data.button_icon);
                $button.find(".button-text").text(response.data.button_text);
              } else {
                $button.removeClass("not-shopgw-added").addClass("shopgw-added");
                $button.find("i").attr("class", response.data.button_icon);
                $button.find(".button-text").text(response.data.button_text);
              }
              
              // Simple repaint to fix Astra theme issues
              forceElementRepaint($button[0]);
            } else {
              $button.attr("href", response.data.href);
              $button.addClass(response.data.class ? response.data.class : "");
              $button.removeClass("not-shopgw-added");
              $button.find("i").attr("class", response.data.button_icon);
              $button.find(".button-text").text(response.data.button_text);
              $button.off("click");
            }

            // Update wishlist counter from toggle response (no separate AJAX needed)
            if (response.data.counter) {
              updateCounterDisplay(response.data.counter.count, response.data.counter.animation);
            }

            // Show Notification with selected effect
            if (ajax_data.notification_type === "side-notification") {
              showSideNotification(
                response.data.notification_text,
                ajax_data.notification_position,
                ajax_data.side_notification_effect,
                isAdded
              );
            } else if (ajax_data.notification_type === "popup-notification") {
              showPopupNotification(response.data.notification_text, ajax_data.popup_notification_effect, isAdded);
            } else {
              // Fallback to basic notification (even if notification is "off", show something)
              const statusClass = isAdded ? "wishlist-removed" : "success-added";
              const message = `<div class='${statusClass}'><i class='fa ${isAdded ? "fa-times-circle" : "fa-check-circle"}'></i> <span>${response.data.notification_text || (isAdded ? 'Removed from wishlist' : 'Added to wishlist')}</span></div>`;
              showNotification(message);
            }
          }
        },
        error: function(xhr, status, error) {
          showNotification("<div class='wishlist-removed'><i class='fa fa-times-circle'></i> <span>Error processing request</span></div>");
        }
      });
    }
  );

  // Side notification functionality
  function showSideNotification(message, position, effect, isAdded) {
    const statusClass = isAdded ? "removed" : "added";

    const $notification = $("<div class='shog-wishlist-notification side-notification'></div>").text(message);
    $notification.addClass(position);
    $notification.addClass(statusClass);
    $("body").append($notification);

    // Apply the selected effect
    switch (effect) {
      case "slide-down-up":
        $notification
          .hide()
          .slideDown()
          .delay(5000)
          .slideUp(function () {
            $(this).remove();
          });
        break;

      case "slide-from-left":
        $notification
          .css({left: "-100px", right: "auto"})
          .animate({left: "7px"}, 500)
          .delay(5000)
          .animate({left: "-210px"}, 500, function () {
            $(this).remove();
          });
        break;

      case "slide-from-right":
        $notification
          .css({right: "-100px", left: "auto"})
          .animate({right: "7px"}, 500)
          .delay(5000)
          .animate({right: "-210px"}, 500, function () {
            $(this).remove();
          });
        break;

      case "bounce":
        $notification
          .css({top: "-=30px", opacity: 0})
          .animate({top: "+=30px", opacity: 1}, 300)
          .delay(5000)
          .animate({top: "-=10px"}, 100)
          .animate({top: "+=20px"}, 100)
          .animate({top: "-=10px"}, 100, function () {
            $(this).fadeOut().remove();
          });
        break;

      default:
        $notification
          .fadeIn()
          .delay(5000)
          .fadeOut(function () {
            $(this).remove();
          });
    }
  }

  // Popup notification functionality
  function showPopupNotification(message, effect, isAdded) {
    const statusClass = isAdded ? "removed" : "added";

    const $popup = $("<div class='shog-wishlist-notification popup-notification'></div>").text(message);
    $popup.addClass(statusClass);
    $("body").append($popup);

    // Apply the selected effect
    switch (effect) {
      case "zoom-in":
        $popup
          .css({transform: "scale(0) translate(0%, 0%)", opacity: 1})
          .appendTo("body")
          .delay(50)
          .queue(function (next) {
            $(this).css({transform: "scale(1) translate(0%, 0%)"});
            next();
          })
          .delay(5000)
          .queue(function (next) {
            $(this).css({transform: "scale(0) translate(0%, 0%)"});
            next();
          })
          .delay(500)
          .fadeOut(function () {
            $(this).remove();
          });
        break;

      case "bounce":
        $popup
          .css({top: "-=50%", opacity: 0})
          .animate({top: "+=50%", opacity: 1}, 300)
          .delay(5000)
          .animate({top: "-=10px"}, 100)
          .animate({top: "+=20px"}, 100)
          .animate({top: "-=10px"}, 100, function () {
            $(this).fadeOut().remove();
          });
        break;

      case "shake":
        $popup
          .css({display: "block", opacity: 1})
          .delay(5000)
          .fadeOut(function () {
            $(this).remove();
          });
        break;

      case "drop-in":
        $popup
          .css({top: "-100px"})
          .animate({top: "50%"}, 500)
          .delay(5000)
          .animate({top: "-50%"}, 500)
          .fadeOut(function () {
            $(this).remove();
          });
        break;
        
      default:
        $popup
          .fadeIn()
          .delay(5000)
          .fadeOut(function () {
            $(this).remove();
          });
    }
  }

  // Basic checkout functionality
  $(document).on("click", ".shopglut-wishlist-table .checkout-link, .shopglut_wishlist .checkout-link", function (e) {
    e.preventDefault();
    var productID = $(this).data("product-id");
    const quantity = $(this).closest("tr").find(".quantity").val() || 1;

    $.ajax({
      url: ajax_data.ajax_url,
      type: "POST",
      data: {
        action: "shopglut_add_to_cart_and_checkout",
        product_id: productID,
        quantity: quantity,
      },
      success: function (response) {
        if (response.success) {
          window.location.href = response.data.redirect_url;
        }
      },
    });
  });


 

  // Make core functions available globally for pro plugin
  window.shopglutWishlist = {
    showNotification: showNotification,
    loadWishlistContent: loadWishlistContent,
    removeItemFromWishlist: removeItemFromWishlist,
    getOrCreateGuestId: getOrCreateGuestId,
    updateCounterDisplay: updateCounterDisplay
  };
  
  // Test function - you can call this in browser console to test notifications
  window.testWishlistNotification = function() {
    showNotification("<div class='success-added'><i class='fa fa-check-circle'></i> <span>Test notification - Added to wishlist!</span></div>");
  };
});