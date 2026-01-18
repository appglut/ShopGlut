<?php

namespace Shopglut\enhancements\wishlist;

class dynamicStyle {

	public function dynamicCss() {
		// Retrieve the saved enhancements for the wishlist notifications
		$enhancements = get_option( 'agshopglut_wishlist_options' );

		$notificationType = isset( $enhancements['wishlist-general-notification'] ) ? $enhancements['wishlist-general-notification'] : 'notification-off';
		$notificationAddedBGColor = isset( $enhancements['wishlist-notification-added-bg-color'] ) ? $enhancements['wishlist-notification-added-bg-color'] : 'rgba(45,206,24,0.68)';
		$notificationRemovedBGColor = isset( $enhancements['wishlist-notification-removed-bg-color'] ) ? $enhancements['wishlist-notification-removed-bg-color'] : 'rgba(221,8,8,0.68)';
		$notificationFontColor = isset( $enhancements['wishlist-notification-font-color'] ) ? $enhancements['wishlist-notification-font-color'] : '#fff';
		$sidePosition = isset( $enhancements['wishlist-side-notification-appear'] ) ? $enhancements['wishlist-side-notification-appear'] : 'bottom-right';
		$sideEffect = isset( $enhancements['wishlist-side-notification-effect'] ) ? $enhancements['wishlist-side-notification-effect'] : 'fade-in-out';
		$popupEffect = isset( $enhancements['wishlist-popup-notification-effect'] ) ? $enhancements['wishlist-popup-notification-effect'] : 'fade-in-out';

		// Button styles for Wishlist
		$wishlistButtonWidth = isset( $enhancements['wishlist-product-wishlist-button-width'] ) && is_array( $enhancements['wishlist-product-wishlist-button-width'] ) ? $enhancements['wishlist-product-wishlist-button-width'] : array( 'width' => '175', 'unit' => 'px' );
		$wishlistButtonColor = isset( $enhancements['wishlist-product-button-color'] ) ? $enhancements['wishlist-product-button-color'] : '#0073aa';
		$wishlistButtonFontColor = isset( $enhancements['wishlist-product-button-font-color'] ) ? $enhancements['wishlist-product-button-font-color'] : '#fff';
		$wishlistButtonPadding = isset( $enhancements['wishlist-product-button-padding'] ) && is_array( $enhancements['wishlist-product-button-padding'] ) ? $enhancements['wishlist-product-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$wishlistButtonMargin = isset( $enhancements['wishlist-product-button-margin'] ) && is_array( $enhancements['wishlist-product-button-margin'] ) ? $enhancements['wishlist-product-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );
		$wishlistIconColor = isset( $enhancements['wishlist-product-icon-color'] ) ? $enhancements['wishlist-product-icon-color'] : '#fff';

		// Button styles for Move to List
		$moveButtonColor = isset( $enhancements['wishlist-product-move-button-color'] ) ? $enhancements['wishlist-product-move-button-color'] : '#0073aa';
		$moveButtonFontColor = isset( $enhancements['wishlist-product-move-button-font-color'] ) ? $enhancements['wishlist-product-move-button-font-color'] : '#fff';
		$moveButtonPadding = isset( $enhancements['wishlist-product-move-button-padding'] ) && is_array( $enhancements['wishlist-product-move-button-padding'] ) ? $enhancements['wishlist-product-move-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$moveButtonMargin = isset( $enhancements['wishlist-product-move-button-margin'] ) && is_array( $enhancements['wishlist-product-move-button-margin'] ) ? $enhancements['wishlist-product-move-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );

		$wishlistLockedButtonColor = isset( $enhancements['wishlist-locked-background'] ) ? $enhancements['wishlist-locked-background'] : '#dd3333';
		$wishlistLockedButtonFontColor = isset( $enhancements['wishlist-locked-font-color'] ) ? $enhancements['wishlist-locked-font-color'] : '#fff';
		$wishlistLockedIconColor = isset( $enhancements['wishlist-locked-icon-color'] ) ? $enhancements['wishlist-locked-icon-color'] : '#fff';

		$tabColor = isset( $enhancements['wishlist-page-list-tab-color'] ) ? $enhancements['wishlist-page-list-tab-color'] : '#a3a3a3';
		$tabFontColor = isset( $enhancements['wishlist-page-list-tab-font-color'] ) ? $enhancements['wishlist-page-list-tab-font-color'] : '#fff';
		$activeTabColor = isset( $enhancements['wishlist-page-list-active-tab-color'] ) ? $enhancements['wishlist-page-list-active-tab-color'] : '#fff';
		$activeTabFontColor = isset( $enhancements['wishlist-page-list-active-tab-font-color'] ) ? $enhancements['wishlist-page-list-active-tab-font-color'] : '#000';

		// Table header colors
		$tableHeaderColor = isset( $enhancements['wishlist-page-table-header-color'] ) ? $enhancements['wishlist-page-table-header-color'] : '#a3a3a3';
		$tableHeaderFontColor = isset( $enhancements['wishlist-page-table-head-font-color'] ) ? $enhancements['wishlist-page-table-head-font-color'] : '#fff';

		$subscribeButtonColor = isset( $enhancements['wishlist-page-subscription-btn-color'] ) ? $enhancements['wishlist-page-subscription-btn-color'] : '#0073aa';
		$subscribeButtonFontColor = isset( $enhancements['wishlist-page-subscription-btn-font-color'] ) ? $enhancements['wishlist-page-subscription-btn-font-color'] : '#fff';
		// Table body colors
		$bodyColorChoice = isset( $enhancements['wishlist-page-body-color-choice'] ) ? $enhancements['wishlist-page-body-color-choice'] : 'body-same-color';
		$tableBodyColor = isset( $enhancements['wishlist-page-body-color'] ) ? $enhancements['wishlist-page-body-color'] : '#fff';
		$oddRowColor = isset( $enhancements['wishlist-page-body-odd-color'] ) ? $enhancements['wishlist-page-body-odd-color'] : '#fff';
		$evenRowColor = isset( $enhancements['wishlist-page-body-even-color'] ) ? $enhancements['wishlist-page-body-even-color'] : '#f1f1f1';
		$hoverColor = isset( $enhancements['wishlist-page-body-hover-color'] ) ? $enhancements['wishlist-page-body-hover-color'] : '#f1f1f1';
		$bodyFontColor = isset( $enhancements['wishlist-page-table-body-font-color'] ) ? $enhancements['wishlist-page-table-body-font-color'] : '#000';

		// Button styles
		$addToCartButtonColor = isset( $enhancements['wishlist-page-addtocart-button-color'] ) ? $enhancements['wishlist-page-addtocart-button-color'] : '#0073aa';
		$addToCartButtonFontColor = isset( $enhancements['wishlist-page-addtocart-button-font-color'] ) ? $enhancements['wishlist-page-addtocart-button-font-color'] : '#fff';
		$checkoutButtonColor = isset( $enhancements['wishlist-page-checkout-button-color'] ) ? $enhancements['wishlist-page-checkout-button-color'] : '#0073aa';
		$checkoutButtonFontColor = isset( $enhancements['wishlist-page-checkout-button-font-color'] ) ? $enhancements['wishlist-page-checkout-button-font-color'] : '#fff';

		$ShopButtonWidth = isset( $enhancements['wishlist-shop-wishlist-button-width'] ) && is_array( $enhancements['wishlist-shop-wishlist-button-width'] ) ? $enhancements['wishlist-shop-wishlist-button-width'] : array( 'width' => '175', 'unit' => 'px' );
		$shopButtonColor = isset( $enhancements['wishlist-shop-button-color'] ) ? $enhancements['wishlist-shop-button-color'] : '#0073aa';
		$shopButtonFontColor = isset( $enhancements['wishlist-shop-button-text-color'] ) ? $enhancements['wishlist-shop-button-text-color'] : '#000';
		$shopButtonPadding = isset( $enhancements['wishlist-shop-button-padding'] ) && is_array( $enhancements['wishlist-shop-button-padding'] ) ? $enhancements['wishlist-shop-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$shopButtonMargin = isset( $enhancements['wishlist-shop-button-margin'] ) && is_array( $enhancements['wishlist-shop-button-margin'] ) ? $enhancements['wishlist-shop-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );
		$shopIconColor = isset( $enhancements['wishlist-shop-icon-color'] ) ? $enhancements['wishlist-shop-icon-color'] : '#000';

		$shopMoveButtonColor = isset( $enhancements['wishlist-shop-move-button-color'] ) ? $enhancements['wishlist-shop-move-button-color'] : '#0073aa';
		$shopMoveButtonFontColor = isset( $enhancements['wishlist-shop-move-button-font-color'] ) ? $enhancements['wishlist-shop-move-button-font-color'] : '#000';
		$shopMoveButtonPadding = isset( $enhancements['wishlist-shop-move-button-padding'] ) && is_array( $enhancements['wishlist-shop-move-button-padding'] ) ? $enhancements['wishlist-shop-move-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$shopMoveButtonMargin = isset( $enhancements['wishlist-shop-move-button-margin'] ) && is_array( $enhancements['wishlist-shop-move-button-margin'] ) ? $enhancements['wishlist-shop-move-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );

		// Archive Page Styles
		$archiveButtonWidth = isset( $enhancements['wishlist-archive-wishlist-button-width'] ) && is_array( $enhancements['wishlist-archive-wishlist-button-width'] ) ? $enhancements['wishlist-archive-wishlist-button-width'] : array( 'width' => '175', 'unit' => 'px' );
		$archiveButtonColor = isset( $enhancements['wishlist-archive-button-color'] ) ? $enhancements['wishlist-archive-button-color'] : '#0073aa';
		$archiveButtonFontColor = isset( $enhancements['wishlist-archive-button-text-color'] ) ? $enhancements['wishlist-archive-button-text-color'] : '#000';
		$archiveButtonPadding = isset( $enhancements['wishlist-archive-button-padding'] ) && is_array( $enhancements['wishlist-archive-button-padding'] ) ? $enhancements['wishlist-archive-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$archiveButtonMargin = isset( $enhancements['wishlist-archive-button-margin'] ) && is_array( $enhancements['wishlist-archive-button-margin'] ) ? $enhancements['wishlist-archive-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );
		$archiveIconColor = isset( $enhancements['wishlist-archive-icon-color'] ) ? $enhancements['wishlist-archive-icon-color'] : '#000';

		$archiveMoveButtonColor = isset( $enhancements['wishlist-archive-move-button-color'] ) ? $enhancements['wishlist-archive-move-button-color'] : '#0073aa';
		$archiveMoveButtonFontColor = isset( $enhancements['wishlist-archive-move-button-font-color'] ) ? $enhancements['wishlist-archive-move-button-font-color'] : '#000';
		$archiveMoveButtonPadding = isset( $enhancements['wishlist-archive-move-button-padding'] ) && is_array( $enhancements['wishlist-archive-move-button-padding'] ) ? $enhancements['wishlist-archive-move-button-padding'] : array( 'top' => '15', 'right' => '20', 'bottom' => '15', 'left' => '20', 'unit' => 'px' );
		$archiveMoveButtonMargin = isset( $enhancements['wishlist-archive-move-button-margin'] ) && is_array( $enhancements['wishlist-archive-move-button-margin'] ) ? $enhancements['wishlist-archive-move-button-margin'] : array( 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px' );

		$dynamic_css = "";

		$dynamic_css = "
        .shoglut-wishlist-tabs .tab-title {
            background-color: {$tabColor};
            color: {$tabFontColor};
        }
        .shoglut-wishlist-tabs .tab-title.active {
            background-color: {$activeTabColor};
            color: {$activeTabFontColor};
        }
        .shopglut-wishlist-table thead tr {
            background-color: {$tableHeaderColor};
            color: {$tableHeaderFontColor};
        }
        .shopglut-wishlist-table tbody tr {
            background-color: {$tableBodyColor};
            color: {$bodyFontColor};
        }
        .shopglut-wishlist-table tbody tr:hover {
            background-color: {$hoverColor};
        }
    ";

		// Conditional styling for odd/even rows
		if ( $bodyColorChoice === 'body-oddeven-color' ) {
			$dynamic_css .= "
            .shopglut-wishlist-table tbody tr:nth-child(odd) {
                background-color: {$oddRowColor};
            }
            .shopglut-wishlist-table tbody tr:nth-child(even) {
                background-color: {$evenRowColor};
            }
        ";
		}

		// Button styles for Add to Cart and Checkout
		$dynamic_css .= "
        .shopglut-wishlist-table .add-to-cart-btn {
            background-color: {$addToCartButtonColor} !important;
            color: {$addToCartButtonFontColor} !important;
        }
        .shopglut-wishlist-table .checkout-link {
            background-color: {$checkoutButtonColor} !important;
            color: {$checkoutButtonFontColor} !important;
             background-color: #0073aa;
			 transition: background-color 0.3s;
             font-weight: 400;
             padding: 10px 15px;
             border-radius: 4px;
			 text-decoration: none !important;
        }
    ";

		$dynamic_css .= "
    .shopglutw-subscribe-notification-btn {
        background-color: {$subscribeButtonColor} !important;
        color: {$subscribeButtonFontColor} !important;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

	 .shopglutw-subscribe-notification-btn:hover {
        background-color: {$subscribeButtonColor} !important;
        color: {$subscribeButtonFontColor} !important;
    }
";

		$dynamic_css .= "
            .shopglut_wishlist.single-product .button {
                background-color: {$wishlistButtonColor} !important;
                color: {$wishlistButtonFontColor} !important;
                padding: {$wishlistButtonPadding['top']}{$wishlistButtonPadding['unit']} {$wishlistButtonPadding['right']}{$wishlistButtonPadding['unit']} {$wishlistButtonPadding['bottom']}{$wishlistButtonPadding['unit']} {$wishlistButtonPadding['left']}{$wishlistButtonPadding['unit']} !important;
                margin: {$wishlistButtonMargin['top']}{$wishlistButtonMargin['unit']} {$wishlistButtonMargin['right']}{$wishlistButtonMargin['unit']} {$wishlistButtonMargin['bottom']}{$wishlistButtonMargin['unit']} {$wishlistButtonMargin['left']}{$wishlistButtonMargin['unit']} !important;
                border: none;
                border-radius: 5px !important;
				width: {$wishlistButtonWidth['width']}{$wishlistButtonWidth['unit']} !important;
            }

           .shopglut_wishlist.single-product .button i {
                color: {$wishlistIconColor};
            }
        ";

		$dynamic_css .= "
            .shopglut_wishlist.single-product .button.login-required {
                background-color: {$wishlistLockedButtonColor};
                color: {$wishlistLockedButtonFontColor};
				border: none;
                border-radius: 5px;
            }

           .shopglut_wishlist.single-product .button.login-required i {

                color: {$wishlistLockedIconColor};
            }
        ";

		$dynamic_css .= "
            .shopglut_wishlist_movelist.single-product .button.move_to_list {
                background-color: {$moveButtonColor} !important;
                color: {$moveButtonFontColor} !important;
                padding: {$moveButtonPadding['top']}{$moveButtonPadding['unit']} {$moveButtonPadding['right']}{$moveButtonPadding['unit']} {$moveButtonPadding['bottom']}{$moveButtonPadding['unit']} {$moveButtonPadding['left']}{$moveButtonPadding['unit']};
                margin: {$moveButtonMargin['top']}{$moveButtonMargin['unit']} {$moveButtonMargin['right']}{$moveButtonMargin['unit']} {$moveButtonMargin['bottom']}{$moveButtonMargin['unit']} {$moveButtonMargin['left']}{$moveButtonMargin['unit']};
                border: none;
                border-radius: 5px;
            }
        ";

		$dynamic_css .= "
    .shopglut_wishlist.shop-page .button {
        background-color: {$shopButtonColor};
        color: {$shopButtonFontColor};
        padding: {$shopButtonPadding['top']}{$shopButtonPadding['unit']} {$shopButtonPadding['right']}{$shopButtonPadding['unit']} {$shopButtonPadding['bottom']}{$shopButtonPadding['unit']} {$shopButtonPadding['left']}{$shopButtonPadding['unit']};
        margin: {$shopButtonMargin['top']}{$shopButtonMargin['unit']} {$shopButtonMargin['right']}{$shopButtonMargin['unit']} {$shopButtonMargin['bottom']}{$shopButtonMargin['unit']} {$shopButtonMargin['left']}{$shopButtonMargin['unit']};
        border: none;
        border-radius: 5px;
		width: {$ShopButtonWidth['width']}{$ShopButtonWidth['unit']};
    }
    .shopglut_wishlist.shop-page .button i {
        color: {$shopIconColor};
    }
    .shopglut_wishlist_movelist.shop-page .button.move_to_list {
        background-color: {$shopMoveButtonColor};
        color: {$shopMoveButtonFontColor};
        padding: {$shopMoveButtonPadding['top']}{$shopMoveButtonPadding['unit']} {$shopMoveButtonPadding['right']}{$shopMoveButtonPadding['unit']} {$shopMoveButtonPadding['bottom']}{$shopMoveButtonPadding['unit']} {$shopMoveButtonPadding['left']}{$shopMoveButtonPadding['unit']};
        margin: {$shopMoveButtonMargin['top']}{$shopMoveButtonMargin['unit']} {$shopMoveButtonMargin['right']}{$shopMoveButtonMargin['unit']} {$shopMoveButtonMargin['bottom']}{$shopMoveButtonMargin['unit']} {$shopMoveButtonMargin['left']}{$shopMoveButtonMargin['unit']};
        border: none;
        border-radius: 5px;
    }
";

		$dynamic_css .= "
    .shopglut_wishlist.archive-page .button {
        background-color: {$archiveButtonColor};
        color: {$archiveButtonFontColor};
        padding: {$archiveButtonPadding['top']}{$archiveButtonPadding['unit']} {$archiveButtonPadding['right']}{$archiveButtonPadding['unit']} {$archiveButtonPadding['bottom']}{$archiveButtonPadding['unit']} {$archiveButtonPadding['left']}{$archiveButtonPadding['unit']};
        margin: {$archiveButtonMargin['top']}{$archiveButtonMargin['unit']} {$archiveButtonMargin['right']}{$archiveButtonMargin['unit']} {$archiveButtonMargin['bottom']}{$archiveButtonMargin['unit']} {$archiveButtonMargin['left']}{$archiveButtonMargin['unit']};
        border: none;
        border-radius: 5px;
	    width: {$archiveButtonWidth['width']}{$archiveButtonWidth['unit']};

    }
    .shopglut_wishlist.archive-page .button i {
        color: {$archiveIconColor};
    }
    .shopglut_wishlist_movelist.archive-page .button.move_to_list {
        background-color: {$archiveMoveButtonColor};
        color: {$archiveMoveButtonFontColor};
        padding: {$archiveMoveButtonPadding['top']}{$archiveMoveButtonPadding['unit']} {$archiveMoveButtonPadding['right']}{$archiveMoveButtonPadding['unit']} {$archiveMoveButtonPadding['bottom']}{$archiveMoveButtonPadding['unit']} {$archiveMoveButtonPadding['left']}{$archiveMoveButtonPadding['unit']};
        margin: {$archiveMoveButtonMargin['top']}{$archiveMoveButtonMargin['unit']} {$archiveMoveButtonMargin['right']}{$archiveMoveButtonMargin['unit']} {$archiveMoveButtonMargin['bottom']}{$archiveMoveButtonMargin['unit']} {$archiveMoveButtonMargin['left']}{$archiveMoveButtonMargin['unit']};
        border: none;
        border-radius: 5px;
    }
";

		// Generate CSS for side notifications based on the selected effect
		if ( $notificationType === 'side-notification' ) {
			// Common CSS for the notification container
			$dynamic_css .= "

			.shog-wishlist-notification.side-notification{
			        position: fixed;
                    z-index: 1000;
					display: none;
		   }
				.shog-wishlist-notification.side-notification.added {

					padding: 15px;
					background: {$notificationAddedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
				.shog-wishlist-notification.side-notification.removed {
					padding: 15px;
					background: {$notificationRemovedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
			";

			// Set the position of the notification
			switch ( $sidePosition ) {
				case 'top-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; left: 10px; }";
					break;
				case 'top-middle':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; left: 50%; transform: translateX(-50%); }";
					break;
				case 'top-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; right: 10px; }";
					break;
				case 'middle-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 50%; left: 10px; transform: translateY(-50%); }";
					break;
				case 'middle-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 50%; right: 10px; transform: translateY(-50%); }";
					break;
				case 'bottom-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; left: 10px; }";
					break;
				case 'bottom-middle':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; left: 50%; transform: translateX(-50%); }";
					break;
				case 'bottom-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; right: 10px; }";
					break;
			}

			// Additional styling based on the side effect
			switch ( $sideEffect ) {
				case 'slide-down-up':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display: block;  }";
					break;
				case 'slide-from-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display: block; left: -200px; }";
					break;
				case 'slide-from-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display:block; right: -200px; }";
					break;
				case 'bounce':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { display: block; animation: bounce 0.5s ease-in-out; }";
					break;
				default: // 'fade-in-out' or any unspecified effect
					// No specific CSS needed as fade is handled by jQuery
					break;
			}
		} else {
			// Generate basic notification styles even when notifications are "off" or other types
			// This ensures our fallback notifications have proper positioning
			$dynamic_css .= "
			.shog-wishlist-notification.side-notification{
			        position: fixed;
                    z-index: 1000;
					display: none;
		   }
				.shog-wishlist-notification.side-notification.added {
					padding: 15px;
					background: {$notificationAddedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;
				}
				.shog-wishlist-notification.side-notification.removed {
					padding: 15px;
					background: {$notificationRemovedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;
				}
			";

			// Set the position of the notification (fallback positioning)
			switch ( $sidePosition ) {
				case 'top-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; left: 10px; }";
					break;
				case 'top-middle':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; left: 50%; transform: translateX(-50%); }";
					break;
				case 'top-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 10px; right: 10px; }";
					break;
				case 'middle-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 50%; left: 10px; transform: translateY(-50%); }";
					break;
				case 'middle-right':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { top: 50%; right: 10px; transform: translateY(-50%); }";
					break;
				case 'bottom-left':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; left: 10px; }";
					break;
				case 'bottom-middle':
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; left: 50%; transform: translateX(-50%); }";
					break;
				case 'bottom-right':
				default:
					$dynamic_css .= ".shog-wishlist-notification.side-notification { bottom: 10px; right: 10px; }";
					break;
			}
		}

		// Generate CSS for popup notifications based on the selected effect
		if ( $notificationType === 'popup-notification' ) {
			$dynamic_css .= "

			.shog-wishlist-notification.popup-notification{
			        position: fixed;
					top: 50%;
					left: 50%;
					z-index: 1000;
					display: none;
		       }
				.shog-wishlist-notification.popup-notification.added {

					transform: translate(-50%, -50%);
					padding: 15px;
					background: {$notificationAddedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
				.shog-wishlist-notification.popup-notification.removed {
					transform: translate(-50%, -50%);
					padding: 15px;
					background: {$notificationRemovedBGColor};
					color: {$notificationFontColor};
					border-radius: 4px;

				}
			";

			// Additional styling based on the popup effect
			switch ( $popupEffect ) {
				case 'zoom-in':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification {
                   transform: scale(0) translate(0%, 0%);
                   transition: transform 0.5s ease-in-out;
	               transform-origin: center center; /* Scale from the center */
                   display: block;
	               top:38%;
	               left:40%; } ";
					break;
				case 'drop-in':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification { top: 100px; display: block; }";
					break;
				case 'bounce':
					$dynamic_css .= ".shog-wishlist-notification.popup-notification { display:block; animation: bounce 0.5s ease-in-out; }";
					break;
				case 'shake':
					$dynamic_css .= "
					@keyframes shake {
						0%, 100% { transform: translate(-50%, -50%) translateX(0); }
						10%, 30%, 50%, 70%, 90% { transform: translate(-50%, -50%) translateX(-10px); }
						20%, 40%, 60%, 80% { transform: translate(-50%, -50%) translateX(10px); }
					}
					.shog-wishlist-notification.popup-notification { animation: shake 0.5s ease-in-out; }";
					break;
				default: // 'fade-in-out' or any unspecified effect
					// No specific CSS needed as fade is handled by jQuery
					break;
			}
		}

		// Social Share Styles - with proper defaults for empty values
		$defaults = array(
			'margin' => array( 'top' => '20', 'right' => '0', 'bottom' => '20', 'left' => '0', 'unit' => 'px' ),
			'padding' => array( 'top' => '15', 'right' => '15', 'bottom' => '15', 'left' => '15', 'unit' => 'px' ),
			'titleColor' => '#333333',
			'titleFontSize' => array( 'width' => '16', 'unit' => 'px' ),
			'buttonSize' => array( 'width' => '40', 'height' => '40', 'unit' => 'px' ),
			'buttonSpacing' => array( 'width' => '8', 'unit' => 'px' ),
			'buttonBorderRadius' => array( 'width' => '5', 'unit' => 'px' ),
			'facebookColor' => '#1877f2',
			'twitterColor' => '#1da1f2',
			'whatsappColor' => '#25d366',
			'pinterestColor' => '#bd081c',
			'linkedinColor' => '#0077b5',
			'telegramColor' => '#0088cc',
			'emailColor' => '#666666',
			'iconColor' => '#ffffff',
			'hoverOpacity' => 80
		);

		// Helper function to get value with fallback for empty strings
		$getValue = function($key, $default) use ($enhancements) {
			if (!isset($enhancements[$key])) {
				return $default;
			}
			$value = $enhancements[$key];
			// If it's an array, check if any values are empty
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					if ($v === '' && isset($default[$k])) {
						$value[$k] = $default[$k];
					}
				}
				return $value;
			}
			// If it's a string and empty, return default
			return ($value === '') ? $default : $value;
		};

		$shareContainerMargin = $getValue('social-share-container-margin', $defaults['margin']);
		$shareContainerPadding = $getValue('social-share-container-padding', $defaults['padding']);
		$shareTitleColor = $getValue('social-share-title-color', $defaults['titleColor']);
		$shareTitleFontSize = $getValue('social-share-title-font-size', $defaults['titleFontSize']);
		$shareButtonSize = $getValue('social-share-button-size', $defaults['buttonSize']);
		$shareButtonSpacing = $getValue('social-share-button-spacing', $defaults['buttonSpacing']);
		$shareButtonBorderRadius = $getValue('social-share-button-border-radius', $defaults['buttonBorderRadius']);
		$shareFacebookColor = $getValue('social-share-facebook-color', $defaults['facebookColor']);
		$shareTwitterColor = $getValue('social-share-twitter-color', $defaults['twitterColor']);
		$shareWhatsappColor = $getValue('social-share-whatsapp-color', $defaults['whatsappColor']);
		$sharePinterestColor = $getValue('social-share-pinterest-color', $defaults['pinterestColor']);
		$shareLinkedinColor = $getValue('social-share-linkedin-color', $defaults['linkedinColor']);
		$shareTelegramColor = $getValue('social-share-telegram-color', $defaults['telegramColor']);
		$shareEmailColor = $getValue('social-share-email-color', $defaults['emailColor']);
		$shareIconColor = $getValue('social-share-icon-color', $defaults['iconColor']);

		$shareButtonHoverOpacity = $getValue('social-share-button-hover-opacity', $defaults['hoverOpacity']);
		// Handle if opacity is an array
		if (is_array($shareButtonHoverOpacity)) {
			$shareButtonHoverOpacity = isset($shareButtonHoverOpacity['social-share-button-hover-opacity']) && $shareButtonHoverOpacity['social-share-button-hover-opacity'] !== ''
				? (int)$shareButtonHoverOpacity['social-share-button-hover-opacity']
				: $defaults['hoverOpacity'];
		}
		$shareButtonHoverOpacity = (int)$shareButtonHoverOpacity;
		if ($shareButtonHoverOpacity <= 0) {
			$shareButtonHoverOpacity = $defaults['hoverOpacity'];
		}

		$dynamic_css .= "
			.shopglut-social-share {
				display: flex;
				align-items: center;
				flex-wrap: wrap;
				justify-content: flex-end;
				gap: {$shareButtonSpacing['width']}{$shareButtonSpacing['unit']};
				margin: {$shareContainerMargin['top']}{$shareContainerMargin['unit']} {$shareContainerMargin['right']}{$shareContainerMargin['unit']} {$shareContainerMargin['bottom']}{$shareContainerMargin['unit']} {$shareContainerMargin['left']}{$shareContainerMargin['unit']};
				padding: {$shareContainerPadding['top']}{$shareContainerPadding['unit']} {$shareContainerPadding['right']}{$shareContainerPadding['unit']} {$shareContainerPadding['bottom']}{$shareContainerPadding['unit']} {$shareContainerPadding['left']}{$shareContainerPadding['unit']};
			}

			.shopglut-social-share .share-title {
				color: {$shareTitleColor};
				font-size: {$shareTitleFontSize['width']}{$shareTitleFontSize['unit']};
				margin-right: 10px;
				font-weight: 500;
			}

			.shopglut-social-share .social-share-btn {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				width: {$shareButtonSize['width']}{$shareButtonSize['unit']};
				height: {$shareButtonSize['height']}{$shareButtonSize['unit']};
				border-radius: {$shareButtonBorderRadius['width']}{$shareButtonBorderRadius['unit']};
				text-decoration: none !important;
				transition: opacity 0.3s ease;
				color: {$shareIconColor} !important;
			}

			.shopglut-social-share .social-share-btn:hover {
				opacity: " . ($shareButtonHoverOpacity / 100) . ";
			}

			.shopglut-social-share .social-share-btn i {
				font-size: calc({$shareButtonSize['width']}{$shareButtonSize['unit']} * 0.5);
			}

			.shopglut-social-share .facebook-share {
				background-color: {$shareFacebookColor};
			}

			.shopglut-social-share .twitter-share {
				background-color: {$shareTwitterColor};
			}

			.shopglut-social-share .whatsapp-share {
				background-color: {$shareWhatsappColor};
			}

			.shopglut-social-share .pinterest-share {
				background-color: {$sharePinterestColor};
			}

			.shopglut-social-share .linkedin-share {
				background-color: {$shareLinkedinColor};
			}

			.shopglut-social-share .telegram-share {
				background-color: {$shareTelegramColor};
			}

			.shopglut-social-share .email-share {
				background-color: {$shareEmailColor};
			}
		";

		return $dynamic_css;
	}
}