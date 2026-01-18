/**
 * ShopGlut Product Tab Template1 - WooCommerce Product Carousel JavaScript
 */

class ShopGlutProductTabTemplate1 {
	constructor() {
		this.tabs = [];
		this.init();
	}

	init() {
		this.initializeTabs();
		this.bindEvents();
		this.setupKeyboardNavigation();
		this.initializeAddToCart();
	}

	initializeTabs() {
		const tabElements = document.querySelectorAll('.shopglut-product-tab.template1');

		tabElements.forEach((element, index) => {
			const tab = this.createTabInstance(element, index);
			this.tabs.push(tab);
			tab.init();
		});
	}

	createTabInstance(element, index) {
		const tab = {
			element: element,
			index: index,
			currentSlide: 0,
			totalSlides: 0,
			totalProducts: 0,
			slidesPerView: parseInt(element.dataset.slidesPerView) || 4,
			autoplayTimer: null,
			isTransitioning: false,

			// Tab elements
			wrapper: element.querySelector('.product-tab-wrapper'),
			slides: element.querySelectorAll('.product-slide'),
			arrows: {
				prev: element.querySelector('.tab-prev'),
				next: element.querySelector('.tab-next')
			},
			dots: element.querySelectorAll('.dot-item'),
			products: element.querySelectorAll('.product-card'),

			// Settings
			autoplay: element.dataset.autoplay === 'true',
			autoplaySpeed: parseInt(element.dataset.autoplaySpeed) || 3000,
			animationSpeed: parseInt(element.dataset.animationSpeed) || 500,
			showDots: element.dataset.showDots === 'true',
			showArrows: element.dataset.showArrows === 'true',

			init() {
				this.totalSlides = this.slides.length;
				this.totalProducts = this.products.length;

				// Setup slides per view based on screen size
				this.updateSlidesPerView();

				if (this.totalSlides <= 1) {
					this.hideNavigation();
					return;
				}

				this.setupSlidePositions();
				this.bindEvents();
				this.startAutoplay();
				this.updateActiveStates();
			},

			updateSlidesPerView() {
				// For the new slide grouping, slidesPerView is handled by HTML/CSS
				// Just use the configured value for responsive behavior
				const width = window.innerWidth;
				this.slidesPerView = parseInt(this.element.dataset.slidesPerView) || 4;
			},

			hideNavigation() {
				if (this.arrows.prev) this.arrows.prev.style.display = 'none';
				if (this.arrows.next) this.arrows.next.style.display = 'none';
				const dotsContainer = element.querySelector('.product-tab-dots');
				if (dotsContainer) {
					dotsContainer.style.display = 'none';
				}
			},

			setupSlidePositions() {
				if (!this.wrapper) return;

				// Set up the wrapper for sliding
				this.wrapper.style.display = 'flex';
				this.wrapper.style.transition = `transform ${this.animationSpeed}ms cubic-bezier(0.4, 0, 0.2, 1)`;

				// Each slide takes full width (100%)
				this.slides.forEach((slide, index) => {
					slide.style.flex = `0 0 100%`;
					slide.style.maxWidth = `100%`;
					slide.style.width = `100%`;
				});

				// Adjust wrapper width to accommodate all slides
				this.wrapper.style.width = `${this.totalSlides * 100}%`;
			},

			bindEvents() {
				// Arrow navigation
				if (this.showArrows) {
					if (this.arrows.prev) {
						this.arrows.prev.addEventListener('click', () => this.prevSlide());
					}
					if (this.arrows.next) {
						this.arrows.next.addEventListener('click', () => this.nextSlide());
					}
				}

				// Dots navigation
				if (this.showDots) {
					this.dots.forEach((dot, index) => {
						dot.addEventListener('click', () => this.goToSlide(index));
					});
				}

				// Pause on hover
				this.element.addEventListener('mouseenter', () => this.pauseAutoplay());
				this.element.addEventListener('mouseleave', () => this.startAutoplay());

				// Touch support
				this.setupTouchGestures();

				// Window resize
				window.addEventListener('resize', () => this.handleResize());
			},

			setupTouchGestures() {
				let startX = 0;
				let endX = 0;
				let isDragging = false;

				const handleStart = (e) => {
					isDragging = true;
					startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
					this.pauseAutoplay();
					this.wrapper.style.cursor = 'grabbing';
				};

				const handleMove = (e) => {
					if (!isDragging) return;
					endX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
				};

				const handleEnd = () => {
					if (!isDragging) return;
					isDragging = false;
					this.wrapper.style.cursor = 'grab';

					const diff = startX - endX;
					if (Math.abs(diff) > 50) { // Minimum swipe distance
						if (diff > 0) {
							this.nextSlide(); // Swipe left, go to next
						} else {
							this.prevSlide(); // Swipe right, go to prev
						}
					}

					this.startAutoplay();
				};

				// Mouse events
				this.wrapper.addEventListener('mousedown', handleStart);
				this.wrapper.addEventListener('mousemove', handleMove);
				this.wrapper.addEventListener('mouseup', handleEnd);
				this.wrapper.addEventListener('mouseleave', handleEnd);

				// Touch events
				this.wrapper.addEventListener('touchstart', handleStart);
				this.wrapper.addEventListener('touchmove', handleMove);
				this.wrapper.addEventListener('touchend', handleEnd);

				// Initial cursor style
				this.wrapper.style.cursor = 'grab';
			},

			handleResize() {
				// Update slides per view and recalculate positions
				const oldSlidesPerView = this.slidesPerView;
				const oldCurrentSlide = this.currentSlide;

				this.updateSlidesPerView();

				// If slides per view changed, adjust current slide
				if (oldSlidesPerView !== this.slidesPerView) {
					// Try to maintain approximate position
					const oldPosition = oldCurrentSlide * oldSlidesPerView;
					this.currentSlide = Math.min(Math.floor(oldPosition / this.slidesPerView), this.totalSlides);
				}

				// Recalculate slide positions
				this.setupSlidePositions();

				// Hide/show navigation based on new slide count
				if (this.totalProducts <= this.slidesPerView) {
					this.hideNavigation();
				} else {
					// Show navigation if it was hidden
					if (this.arrows.prev) this.arrows.prev.style.display = '';
					if (this.arrows.next) this.arrows.next.style.display = '';
					const dotsContainer = this.element.querySelector('.product-tab-dots');
					if (dotsContainer && this.showDots) {
						dotsContainer.style.display = '';
					}
				}

				this.goToSlide(Math.min(this.currentSlide, this.totalSlides), false); // No animation on resize
			},

			goToSlide(index, animate = true) {
				if (this.isTransitioning || index === this.currentSlide) return;
				if (index < 0 || index >= this.totalSlides) return;

				this.isTransitioning = true;
				this.currentSlide = index;

				// Calculate offset - move by full slide width (100%)
				const offset = index * 100;

				// Update wrapper transform
				if (animate) {
					this.wrapper.style.transition = `transform ${this.animationSpeed}ms cubic-bezier(0.4, 0, 0.2, 1)`;
				} else {
					this.wrapper.style.transition = 'none';
				}
				this.wrapper.style.transform = `translateX(-${offset}%)`;

				// Update active states
				this.updateActiveStates();

				// Reset transition flag
				if (animate) {
					setTimeout(() => {
						this.isTransitioning = false;
					}, this.animationSpeed);
				} else {
					this.isTransitioning = false;
				}

				// Restart autoplay
				this.restartAutoplay();
			},

			nextSlide() {
				const nextIndex = (this.currentSlide + 1) % this.totalSlides;
				this.goToSlide(nextIndex);
			},

			prevSlide() {
				const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
				this.goToSlide(prevIndex);
			},

			updateActiveStates() {
				// Update dots
				if (this.showDots) {
					this.dots.forEach((dot, index) => {
						dot.classList.toggle('active', index === this.currentSlide);
					});
				}

				// Update arrow states
				if (this.showArrows) {
					if (this.arrows.prev) {
						this.arrows.prev.disabled = this.currentSlide === 0;
					}
					if (this.arrows.next) {
						this.arrows.next.disabled = this.currentSlide === this.totalSlides - 1;
					}
				}
			},

			startAutoplay() {
				if (!this.autoplay || this.totalSlides <= 1) return;

				this.stopAutoplay();
				this.autoplayTimer = setInterval(() => {
					this.nextSlide();
				}, this.autoplaySpeed);
			},

			pauseAutoplay() {
				if (this.autoplayTimer) {
					clearInterval(this.autoplayTimer);
					this.autoplayTimer = null;
				}
			},

			stopAutoplay() {
				this.pauseAutoplay();
			},

			restartAutoplay() {
				if (this.autoplay) {
					this.stopAutoplay();
					this.startAutoplay();
				}
			},

			destroy() {
				this.stopAutoplay();
				// Clean up event listeners if needed
			}
		};

		return tab;
	}

	bindEvents() {
		// Handle visibility change to pause/resume autoplay
		document.addEventListener('visibilitychange', () => {
			this.tabs.forEach(tab => {
				if (document.hidden) {
					tab.pauseAutoplay();
				} else {
					tab.startAutoplay();
				}
			});
		});
	}

	setupKeyboardNavigation() {
		document.addEventListener('keydown', (e) => {
			// Only handle keyboard events when tab is in focus or visible
			const focusedTab = document.activeElement.closest('.shopglut-product-tab.template1');

			if (focusedTab || this.tabs.some(s => s.element.contains(document.activeElement))) {
				const activeTab = this.tabs.find(s =>
					s.element.contains(document.activeElement) || s.element === focusedTab
				);

				if (!activeTab) return;

				switch (e.key) {
					case 'ArrowLeft':
						e.preventDefault();
						activeTab.prevSlide();
						break;
					case 'ArrowRight':
						e.preventDefault();
						activeTab.nextSlide();
						break;
					case 'Home':
						e.preventDefault();
						activeTab.goToSlide(0);
						break;
					case 'End':
						e.preventDefault();
						activeTab.goToSlide(activeTab.totalSlides - 1);
						break;
					case ' ':
					case 'Spacebar':
						e.preventDefault();
						if (activeTab.autoplayTimer) {
							activeTab.pauseAutoplay();
						} else {
							activeTab.startAutoplay();
						}
						break;
				}
			}
		});
	}

	initializeAddToCart() {
		// Add to cart functionality
		document.addEventListener('click', (e) => {
			const addToCartBtn = e.target.closest('.add-to-cart-btn');
			if (addToCartBtn) {
				e.preventDefault();
				this.handleAddToCart(addToCartBtn);
			}
		});
	}

	handleAddToCart(button) {
		const productId = button.dataset.productId;
		if (!productId) return;

		// Show loading state
		const originalContent = button.innerHTML;
		button.innerHTML = '<span class="loading-spinner">⏳</span>';
		button.disabled = true;

		// Add to cart via AJAX
		this.addToCartAjax(productId, button, originalContent);
	}

	addToCartAjax(productId, button, originalContent) {
		// Check if WooCommerce AJAX add to cart is available
		if (typeof wc_add_to_cart_params !== 'undefined') {
			// Use WooCommerce's built-in AJAX add to cart
			jQuery.ajax({
				type: 'POST',
				url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
				data: {
					product_id: productId,
					quantity: 1
				},
				success: (response) => {
					if (response.error && response.product_url) {
						window.location = response.product_url;
						return;
					}

					// Trigger WooCommerce events
					jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);

					// Show success state
					button.innerHTML = '✓';
					button.style.background = '#28a745';
					button.style.color = '#ffffff';

					// Reset button after 2 seconds
					setTimeout(() => {
						button.innerHTML = originalContent;
						button.style.background = '';
						button.style.color = '';
						button.disabled = false;
					}, 2000);

					// Optional: Show notification
					this.showNotification('Product added to cart!', 'success');
				},
				error: () => {
					// Reset button on error
					button.innerHTML = originalContent;
					button.disabled = false;
					this.showNotification('Failed to add product to cart.', 'error');
				}
			});
		} else {
			// Fallback: redirect to add to cart URL
			const addToCartUrl = '?add-to-cart=' + productId;
			window.location.href = addToCartUrl;
		}
	}

	showNotification(message, type = 'success') {
		// Use centralized ShopGlutNotification utility
		if (typeof ShopGlutNotification !== 'undefined') {
			ShopGlutNotification.show(message, type, { duration: 3000 });
			return;
		}

		// Fallback if centralized utility not loaded
		const notification = document.createElement('div');
		notification.className = `shopglut-notification shopglut-notification-${type}`;
		notification.textContent = message;
		notification.style.cssText = `
			position: fixed;
			top: 20px;
			right: 20px;
			background: ${type === 'success' ? '#28a745' : '#dc3545'};
			color: #ffffff;
			padding: 12px 20px;
			border-radius: 6px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			z-index: 999999;
			font-size: 14px;
			font-weight: 500;
			transform: translateX(100%);
			transition: transform 0.3s ease;
		`;

		document.body.appendChild(notification);

		// Show notification
		setTimeout(() => {
			notification.style.transform = 'translateX(0)';
		}, 100);

		// Hide notification after 3 seconds
		setTimeout(() => {
			notification.style.transform = 'translateX(100%)';
			setTimeout(() => {
				document.body.removeChild(notification);
			}, 300);
		}, 3000);
	}

	// Public methods for external control
	getTab(index) {
		return this.tabs[index];
	}

	getTabByElement(element) {
		return this.tabs.find(s => s.element === element || s.element.contains(element));
	}

	pauseAll() {
		this.tabs.forEach(tab => tab.pauseAutoplay());
	}

	playAll() {
		this.tabs.forEach(tab => tab.startAutoplay());
	}

	destroy() {
		this.tabs.forEach(tab => tab.destroy());
		this.tabs = [];
	}
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	window.ShopGlutProductTabTemplate1 = new ShopGlutProductTabTemplate1();
});

// For dynamic content
if (typeof window !== 'undefined') {
	window.ShopGlutProductTabTemplate1Class = ShopGlutProductTabTemplate1;
}