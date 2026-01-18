// jQuery(document).ready(function ($) {
    
//     function updateWishlistCount() {
//         $.ajax({
//             url:  shopglut_wishlist_ajax.ajax_url,
//             type: 'POST',
//             data: {
//                 action: 'update_wishlist_count',
//                 nonce: shopglut_wishlist_ajax.nonce
//             },
//             success: function(response) {
//                 if (response.success) {
//                     $('.counter-bubble').each(function() {
//                         $(this)
//                             .text(response.data.count)
//                             .addClass(response.data.animation);
                        
//                         // Remove animation class after animation completes
//                         setTimeout(() => {
//                             $(this).removeClass(response.data.animation);
//                         }, 500);
//                     });
//                 }
//             }
//         });
//     }
    
//     // Update count on page load
//     updateWishlistCount();
    
//     // Function to handle wishlist button clicks (removed - counter now updates from toggle response)
//     // The counter is now updated directly from the wishlist toggle response in wishlist.js
//     // This eliminates the need for a separate AJAX request and improves performance

// });