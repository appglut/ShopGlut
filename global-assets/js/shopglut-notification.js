/**
 * ShopGlut Notification System - Centralized Utility
 * Provides a consistent notification interface for all modules
 * Version: 1.0.0
 *
 * Usage:
 *   ShopGlutNotification.show(message, 'success', options);
 *   ShopGlutNotification.show(message, 'error');
 *   ShopGlutNotification.show(message, 'info', { position: 'top-right', duration: 5000 });
 */

(function(window, document) {
	'use strict';

	var ShopGlutNotification = {

		// Default configuration
		config: {
			// Default position: 'bottom-right' (admin) or 'top-right' (frontend)
			position: 'bottom-right',
			// Auto-hide duration in milliseconds (0 = no auto-hide)
			duration: 4000,
			// Whether to show close button
			showCloseButton: true,
			// Container class prefix
			classPrefix: 'shopglut-notification'
		},

		// Current notification element
		currentNotification: null,

		// Timer for auto-hide
		hideTimer: null,

		/**
		 * Initialize notification system
		 * @param {Object} options - Configuration options
		 */
		init: function(options) {
			if (options) {
				this.config = this.extend({}, this.config, options);
			}
		},

		/**
		 * Show a notification
		 * @param {string} message - Notification message (can contain HTML)
		 * @param {string} type - Notification type: 'success', 'error', 'info', 'warning'
		 * @param {Object} options - Override options for this notification
		 * @return {HTMLElement} The notification element
		 */
		show: function(message, type, options) {
			type = type || 'info';
			options = options || {};

			// Clear any existing notification
			this.clear();

			// Merge options with defaults
			var settings = this.extend({}, this.config, options);

			// Create notification element
			var notification = this.createNotification(message, type, settings);
			document.body.appendChild(notification);

			// Store reference
			this.currentNotification = notification;

			// Trigger reflow for animation
			void notification.offsetWidth;

			// Show notification
			notification.classList.add('show');

			// Auto-hide if duration is set
			if (settings.duration > 0) {
				this.hideTimer = setTimeout(function() {
					this.hide();
				}.bind(this), settings.duration);
			}

			return notification;
		},

		/**
		 * Create notification element
		 * @param {string} message - Notification message
		 * @param {string} type - Notification type
		 * @param {Object} settings - Configuration settings
		 * @return {HTMLElement} Notification element
		 */
		createNotification: function(message, type, settings) {
			var notification = document.createElement('div');
			var className = settings.classPrefix;

			// Determine if frontend or admin
			var isFrontend = settings.position === 'top-right' ||
			                 (typeof window.shopglutFrontend !== 'undefined' && window.shopglutFrontend);

			if (isFrontend) {
				className = 'shopglut-frontend-notification';
			}

			// Add base class and type class
			notification.className = className;
			notification.classList.add(type);

			// Add message
			notification.innerHTML = '<span class="notification-message">' + message + '</span>';

			// Add close button if enabled
			if (settings.showCloseButton) {
				var closeBtn = document.createElement('button');
				closeBtn.className = 'close-notification';
				closeBtn.innerHTML = '&times;';
				closeBtn.setAttribute('aria-label', 'Close notification');
				closeBtn.onclick = function() {
					this.hide();
				}.bind(this);
				notification.appendChild(closeBtn);
			}

			// Allow click on notification to dismiss (optional, can be enabled via options)
			if (settings.clickToClose) {
				notification.style.cursor = 'pointer';
				notification.onclick = function(e) {
					if (e.target.className !== 'close-notification') {
						this.hide();
					}
				}.bind(this);
			}

			return notification;
		},

		/**
		 * Hide current notification
		 */
		hide: function() {
			// Clear auto-hide timer
			if (this.hideTimer) {
				clearTimeout(this.hideTimer);
				this.hideTimer = null;
			}

			// Hide notification if exists
			if (this.currentNotification) {
				this.currentNotification.classList.remove('show');

				// Remove from DOM after animation
				setTimeout(function() {
					if (this.currentNotification && this.currentNotification.parentNode) {
						this.currentNotification.parentNode.removeChild(this.currentNotification);
					}
					this.currentNotification = null;
				}.bind(this), 300);
			}
		},

		/**
		 * Clear/remove current notification immediately
		 */
		clear: function() {
			if (this.hideTimer) {
				clearTimeout(this.hideTimer);
				this.hideTimer = null;
			}

			if (this.currentNotification && this.currentNotification.parentNode) {
				this.currentNotification.parentNode.removeChild(this.currentNotification);
			}
			this.currentNotification = null;
		},

		/**
		 * Show success notification
		 */
		success: function(message, options) {
			return this.show(message, 'success', options);
		},

		/**
		 * Show error notification
		 */
		error: function(message, options) {
			return this.show(message, 'error', options);
		},

		/**
		 * Show info notification
		 */
		info: function(message, options) {
			return this.show(message, 'info', options);
		},

		/**
		 * Show warning notification
		 */
		warning: function(message, options) {
			return this.show(message, 'warning', options);
		},

		/**
		 * Extend object utility (shallow merge)
		 */
		extend: function(target) {
			for (var i = 1; i < arguments.length; i++) {
				var source = arguments[i];
				for (var key in source) {
					if (source.hasOwnProperty(key)) {
						target[key] = source[key];
					}
				}
			}
			return target;
		}
	};

	// Expose to global scope
	window.ShopGlutNotification = ShopGlutNotification;

	// Also expose as showNotification for backward compatibility
	window.showNotification = function(message, type, options) {
		return ShopGlutNotification.show(message, type, options);
	};

})(window, document);
