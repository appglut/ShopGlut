/**
 * ShopGlut Accordion Template1 - Product Information Accordion JavaScript
 */

class ShopGlutAccordionTemplate1 {
	constructor() {
		this.accordions = [];
		this.init();
	}

	init() {
		this.initializeAccordions();
		this.bindEvents();
		this.setupKeyboardNavigation();
	}

	initializeAccordions() {
		const accordionContainers = document.querySelectorAll('.shopglut-accordion-container.template1');

		accordionContainers.forEach((container, index) => {
			const accordionInstance = this.createAccordionInstance(container, index);
			this.accordions.push(accordionInstance);
			accordionInstance.init();
		});
	}

	createAccordionInstance(container, index) {
		const accordion = {
			container: container,
			index: index,

			// Accordion elements
			items: container.querySelectorAll('.accordion-item'),
			headers: container.querySelectorAll('.accordion-header'),
			contents: container.querySelectorAll('.accordion-content'),

			// Settings
			allowMultiple: container.dataset.allowMultiple === 'true',
			animationSpeed: parseInt(container.dataset.animationSpeed) || 300,

			init() {
				this.setupAccessibility();
				this.bindEvents();
			},

			setupAccessibility() {
				// Set up proper ARIA attributes
				this.headers.forEach((header, index) => {
					const item = this.items[index];
					const content = this.contents[index];
					const isExpanded = item.classList.contains('expanded');

					header.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
					header.setAttribute('aria-controls', content.id);
					header.setAttribute('tabindex', '0');

					content.setAttribute('aria-hidden', !isExpanded ? 'true' : 'false');
					content.setAttribute('role', 'region');
					content.setAttribute('aria-labelledby', header.id);
				});
			},

			bindEvents() {
				// Header clicks
				this.headers.forEach((header, index) => {
					header.addEventListener('click', (e) => {
						e.preventDefault();
						this.toggleItem(index);
					});

					// Keyboard navigation
					header.addEventListener('keydown', (e) => {
						this.handleHeaderKeydown(e, index);
					});
				});

				// Handle hash changes for deep linking
				window.addEventListener('hashchange', () => {
					this.handleHashChange();
				});

				// Check for hash on initial load
				if (window.location.hash) {
					this.handleHashChange();
				}
			},

			handleHeaderKeydown(e, currentIndex) {
				let newIndex = currentIndex;
				let preventDefault = false;

				switch (e.key) {
					case 'ArrowDown':
					case 'ArrowRight':
						preventDefault = true;
						newIndex = currentIndex < this.headers.length - 1 ? currentIndex + 1 : 0;
						break;
					case 'ArrowUp':
					case 'ArrowLeft':
						preventDefault = true;
						newIndex = currentIndex > 0 ? currentIndex - 1 : this.headers.length - 1;
						break;
					case 'Home':
						preventDefault = true;
						newIndex = 0;
						break;
					case 'End':
						preventDefault = true;
						newIndex = this.headers.length - 1;
						break;
					case 'Enter':
					case ' ':
						preventDefault = true;
						this.toggleItem(currentIndex);
						break;
				}

				if (preventDefault) {
					e.preventDefault();
					if (newIndex !== currentIndex) {
						this.headers[newIndex].focus();
					}
				}
			},

			toggleItem(index) {
				const item = this.items[index];
				const isExpanded = item.classList.contains('expanded');

				if (isExpanded) {
					this.collapseItem(index);
				} else {
					this.expandItem(index);
				}
			},

			expandItem(index) {
				const item = this.items[index];
				const content = this.contents[index];
				const header = this.headers[index];

				// If multiple expansion is not allowed, collapse all other items
				if (!this.allowMultiple) {
					this.items.forEach((otherItem, otherIndex) => {
						if (otherIndex !== index && otherItem.classList.contains('expanded')) {
							this.collapseItem(otherIndex);
						}
					});
				}

				// Expand the target item
				item.classList.add('expanded');
				header.setAttribute('aria-expanded', 'true');
				content.setAttribute('aria-hidden', 'false');

				// Show content with animation
				content.style.display = 'block';

				// Trigger reflow for animation
				content.offsetHeight;

				// Set max height for animation
				const scrollHeight = content.scrollHeight;
				content.style.maxHeight = scrollHeight + 'px';

				// Update hash if accordion has an ID
				this.updateHash(index);

				// Scroll item into view if needed
				this.scrollIntoView(item);

				// Trigger custom event
				this.dispatchAccordionEvent('expand', index);
			},

			collapseItem(index) {
				const item = this.items[index];
				const content = this.contents[index];
				const header = this.headers[index];

				item.classList.remove('expanded');
				header.setAttribute('aria-expanded', 'false');
				content.setAttribute('aria-hidden', 'true');

				// Animate collapse
				content.style.maxHeight = '0px';

				// Hide content after animation
				setTimeout(() => {
					if (!item.classList.contains('expanded')) {
						content.style.display = 'none';
					}
				}, this.animationSpeed);

				// Trigger custom event
				this.dispatchAccordionEvent('collapse', index);
			},

			scrollIntoView(item) {
				const rect = item.getBoundingClientRect();
				const isVisible = rect.top >= 0 && rect.bottom <= window.innerHeight;

				if (!isVisible) {
					item.scrollIntoView({
						behavior: 'smooth',
						block: 'nearest',
						inline: 'nearest'
					});
				}
			},

			updateHash(index) {
				if (this.headers[index]) {
					const accordionId = this.items[index].dataset.accordionId;
					if (accordionId) {
						history.replaceState(null, null, `#${accordionId}`);
					}
				}
			},

			handleHashChange() {
				const hash = window.location.hash.substring(1);
				if (hash) {
					const targetItem = Array.from(this.items).find(item => item.dataset.accordionId === hash);
					if (targetItem) {
						const targetIndex = Array.from(this.items).indexOf(targetItem);
						if (!targetItem.classList.contains('expanded')) {
							this.expandItem(targetIndex);
						}
						// Scroll to the item
						setTimeout(() => {
							targetItem.scrollIntoView({
								behavior: 'smooth',
								block: 'center'
							});
						}, 100);
					}
				}
			},

			dispatchAccordionEvent(action, index) {
				const event = new CustomEvent('shopglutAccordionChange', {
					detail: {
						container: this.container,
						action: action,
						index: index,
						accordionId: this.items[index]?.dataset.accordionId,
						expandedItems: Array.from(this.items)
							.map((item, i) => ({ index: i, id: item.dataset.accordionId, expanded: item.classList.contains('expanded') }))
					},
					bubbles: true
				});
				this.container.dispatchEvent(event);
			},

			// Public methods
			expandById(accordionId) {
				const targetIndex = Array.from(this.items).findIndex(item => item.dataset.accordionId === accordionId);
				if (targetIndex !== -1) {
					this.expandItem(targetIndex);
				}
			},

			collapseById(accordionId) {
				const targetIndex = Array.from(this.items).findIndex(item => item.dataset.accordionId === accordionId);
				if (targetIndex !== -1) {
					this.collapseItem(targetIndex);
				}
			},

			getExpandedItems() {
				return Array.from(this.items)
					.map((item, index) => ({
						index: index,
						id: item.dataset.accordionId,
						expanded: item.classList.contains('expanded')
					}))
					.filter(item => item.expanded);
			},

			expandAll() {
				if (this.allowMultiple) {
					this.items.forEach((item, index) => {
						if (!item.classList.contains('expanded')) {
							this.expandItem(index);
						}
					});
				}
			},

			collapseAll() {
				this.items.forEach((item, index) => {
					if (item.classList.contains('expanded')) {
						this.collapseItem(index);
					}
				});
			},

			destroy() {
				// Clean up event listeners
				this.headers.forEach(header => {
					header.replaceWith(header.cloneNode(true));
				});
			}
		};

		return accordion;
	}

	bindEvents() {
		// Listen for custom accordion events
		document.addEventListener('shopglutAccordionChange', (e) => {
			// Allow external code to react to accordion changes
		});

		// Global keyboard shortcuts
		document.addEventListener('keydown', (e) => {
			// Alt + A to expand all (if multiple is allowed)
			if (e.altKey && e.key === 'a') {
				e.preventDefault();
				this.accordions.forEach(accordion => {
					if (accordion.allowMultiple) {
						accordion.expandAll();
					}
				});
			}

			// Alt + C to collapse all
			if (e.altKey && e.key === 'c') {
				e.preventDefault();
				this.accordions.forEach(accordion => {
					accordion.collapseAll();
				});
			}
		});
	}

	setupKeyboardNavigation() {
		// Additional keyboard navigation setup
	}

	// Public methods for external control
	getAccordion(index) {
		return this.accordions[index];
	}

	getAccordionByElement(element) {
		return this.accordions.find(accordion =>
			accordion.container === element || accordion.container.contains(element)
		);
	}

	getAllAccordions() {
		return this.accordions;
	}

	// Static method to initialize accordions dynamically
	static initialize(selector = '.shopglut-accordion-container.template1') {
		const containers = document.querySelectorAll(selector);
		containers.forEach((container) => {
			// Check if already initialized
			if (!container.hasAttribute('data-shopglut-accordion-initialized')) {
				const instance = new ShopGlutAccordionTemplate1();
				const accordionInstance = instance.createAccordionInstance(container, instance.accordions.length);
				instance.accordions.push(accordionInstance);
				accordionInstance.init();
				container.setAttribute('data-shopglut-accordion-initialized', 'true');
			}
		});
	}

	destroy() {
		this.accordions.forEach(accordion => accordion.destroy());
		this.accordions = [];
	}
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	window.ShopGlutAccordionTemplate1 = new ShopGlutAccordionTemplate1();
});

// For dynamic content
if (typeof window !== 'undefined') {
	window.ShopGlutAccordionTemplate1Class = ShopGlutAccordionTemplate1;

	// Expose the static method for dynamic initialization
	window.ShopGlutAccordionTemplate1Class.initialize = ShopGlutAccordionTemplate1.initialize;
}