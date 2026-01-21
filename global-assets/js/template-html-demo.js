/**
 * Template HTML Demo Preview JavaScript
 * Shared JS for all PreBuilt Layout Template modules
 * Used by: Single Product, Cart, Order Complete, My Account, Product Comparison, Product Quick View, Shop Banner
 */

// Make sure ajax_url and nonce are available globally
if (typeof ajax_url === 'undefined') {
	window.ajax_url = (typeof shopglut_admin_vars !== 'undefined') ? shopglut_admin_vars.ajax_url : '/wp-admin/admin-ajax.php';
}

if (typeof nonce === 'undefined') {
	window.nonce = (typeof shopglut_admin_vars !== 'undefined') ? shopglut_admin_vars.nonce : '';
}

/**
 * Opens the HTML demo modal for a specific module
 * @param {string} templateId - The template identifier (e.g., 'template1', 'sale_red')
 * @param {string} moduleType - The module type ('single-product', 'cart', 'order-complete', 'account', 'product-swatches', 'product-comparison', 'product-quickview', 'product-shopbanner')
 */
function openHtmlDemoModal(templateId, moduleType) {
	

	const modalId = getModalId(moduleType);
	let modal = document.getElementById(modalId);


	// For enhancement modules and showcase modules, create modal if it doesn't exist
	if (!modal && ['product-swatches', 'product-comparison', 'product-quickview', 'product-shopbanner', 'product-gallery', 'product-slider', 'product-tab', 'product-accordion'].includes(moduleType)) {
		modal = createEnhancementModal(moduleType);
	}

	if (modal) {
		// For enhancement modules and showcase modules, and single-product, load content via AJAX first
		if (['single-product', 'product-swatches', 'product-comparison', 'product-quickview', 'product-shopbanner', 'product-gallery', 'product-slider', 'product-tab', 'product-accordion'].includes(moduleType)) {
			loadEnhancementDemoContent(templateId, moduleType, modal);
		} else {
			modal.style.setProperty('display', 'flex', 'important');
			modal.style.setProperty('z-index', '999999', 'important');
		}
	}
}

/**
 * Creates the enhancement modal if it doesn't exist
 * @param {string} moduleType - The module type ('product-comparison', 'product-quickview', or 'product-shopbanner')
 * @returns {HTMLElement} The modal element
 */
function createEnhancementModal(moduleType) {
	let modal = document.getElementById('htmlDemoModal');
	if (modal) {
		return modal;
	}

	modal = document.createElement('div');
	modal.id = 'htmlDemoModal';
	modal.className = 'shopglut-template-modal-image-modal';
	modal.style.display = 'none';
	modal.innerHTML = `
		<div class="shopglut-template-modal-modal-content">
			<button class="shopglut-template-modal-close-modal" onclick="closeHtmlDemoModal('${moduleType}')" aria-label="Close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
			<div class="shopglut-template-modal-modal-body">
			</div>
		</div>
	`;
	document.body.appendChild(modal);
	return modal;
}

/**
 * Loads enhancement demo content via AJAX
 * @param {string} templateId - The template ID
 * @param {string} moduleType - The module type
 * @param {HTMLElement} modal - The modal element
 */
function loadEnhancementDemoContent(templateId, moduleType, modal) {
	// Ensure nonce is always fetched from shopglut_admin_vars if available
	if (typeof shopglut_admin_vars !== 'undefined' && shopglut_admin_vars.nonce) {
		window.nonce = shopglut_admin_vars.nonce;
	}

	const actionMap = {
		'single-product': 'shopglut_get_template_demo_content',
		'product-swatches': 'get_swatches_demo_content',
		'product-comparison': 'get_comparison_demo_content',
		'product-quickview': 'get_quickview_demo_content',
		'product-shopbanner': 'get_shopbanner_demo_content',
		'product-gallery': 'get_gallery_demo_content',
		'product-slider': 'get_slider_demo_content',
		'product-tab': 'get_tab_demo_content',
		'product-accordion': 'get_accordion_demo_content'
	};

	const action = actionMap[moduleType];
	if (!action) {
		console.error('Unknown module type:', moduleType);
		return;
	}

	// Get modal body
	const modalBody = modal.querySelector('.shopglut-template-modal-modal-body');

	// Show modal immediately
	modal.style.setProperty('display', 'flex', 'important');
	modal.style.setProperty('z-index', '999999', 'important');

	// Show a professional loading state
	modalBody.innerHTML = '<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 300px; padding: 40px;"><div style="width: 50px; height: 50px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px;"></div><p style="color: #666; font-size: 16px; margin: 0;">Loading template preview...</p></div><style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>';

	// Load content via AJAX
	// Create timeout promise
	const timeoutPromise = new Promise((_, reject) => {
		setTimeout(() => reject(new Error('Request timeout')), 10000); // 10 second timeout
	});

	// Create fetch promise
	const fetchPromise = fetch(ajax_url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: new URLSearchParams({
			action: action,
			template_id: templateId,
			module_type: moduleType,
			nonce: nonce
		})
	});

	// Race the fetch against timeout
	Promise.race([fetchPromise, timeoutPromise])
	.then(response => response.text())
	.then(html => {
		modalBody.innerHTML = html;
		// Show close button when content loads
		const closeButton = modal.querySelector('.shopglut-template-modal-close-modal');
		if (closeButton) {
			closeButton.classList.add('content-loaded');
		}

		// Initialize WooCommerce tabs for template1 if content was loaded
		if (moduleType === 'single-product') {
			initializeDemoTabs();
		}

		// Fix Shop Banner modal CSS conflicts for demo
		if (moduleType === 'product-shopbanner') {
			const shopBannerModal = modalBody.querySelector('.shopbanner-modal');
			const shopBannerContent = modalBody.querySelector('.shopbanner-modal-content');
			const shopBannerClose = modalBody.querySelector('.shopbanner-close');

			if (shopBannerModal) {
				// Override modal positioning for demo
				shopBannerModal.style.setProperty('position', 'static', 'important');
				shopBannerModal.style.setProperty('opacity', '1', 'important');
				shopBannerModal.style.setProperty('visibility', 'visible', 'important');
				shopBannerModal.style.setProperty('z-index', 'auto', 'important');
				shopBannerModal.style.setProperty('transform', 'none', 'important');
				shopBannerModal.style.setProperty('padding', '0', 'important');
			}

			if (shopBannerContent) {
				// Override content positioning for demo
				shopBannerContent.style.setProperty('position', 'static', 'important');
				shopBannerContent.style.setProperty('transform', 'scale(1)', 'important');
				shopBannerContent.style.setProperty('max-width', '100%', 'important');
				shopBannerContent.style.setProperty('max-height', 'none', 'important');
				shopBannerContent.style.setProperty('box-shadow', 'none', 'important');
				shopBannerContent.style.setProperty('margin', '0', 'important');
			}

			// Hide the template's close button since we have our own
			if (shopBannerClose) {
				shopBannerClose.style.setProperty('display', 'none', 'important');
			}
		}
	})
	.catch(error => {
		console.error('Error loading demo:', error);
		let errorMessage = 'Unable to load template preview';
		if (error.message === 'Request timeout') {
			errorMessage = 'Loading timeout. Please try again.';
		}
		modalBody.innerHTML = `<div style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 300px; padding: 40px; text-align: center;"><div style="color: #dc3545; margin-bottom: 20px;">⚠️</div><p style="color: #dc3545; font-size: 16px; font-weight: 600; margin-bottom: 10px;">${errorMessage}</p><p style="color: #666; font-size: 14px;">Please refresh the page and try again.</p></div>`;
		// Show close button even on error
		const closeButton = modal.querySelector('.shopglut-template-modal-close-modal');
		if (closeButton) {
			closeButton.classList.add('content-loaded');
		}
	});
}

/**
 * Closes the HTML demo modal for a specific module
 * @param {string} moduleType - The module type ('single-product', 'cart', 'order-complete', 'account', 'product-swatches', 'product-comparison', 'product-quickview', 'product-shopbanner')
 */
function closeHtmlDemoModal(moduleType) {
	const modalId = getModalId(moduleType);
	const modal = document.getElementById(modalId);
	if (modal) {
		modal.style.setProperty('display', 'none', 'important');
		document.body.style.overflow = '';
	}
}

/**
 * Gets the modal ID for a specific module type
 * @param {string} moduleType - The module type
 * @returns {string} The modal ID
 */
function getModalId(moduleType) {
	const modalIds = {
		'single-product': 'shopglut-html-demo-modal',
		'cart': 'shopglut-cart-html-demo-modal',
		'order-complete': 'shopglut-ordercomplete-html-demo-modal',
		'account': 'shopglut-account-html-demo-modal',
		'product-swatches': 'htmlDemoModal',
		'product-comparison': 'htmlDemoModal',
		'product-quickview': 'htmlDemoModal',
		'product-shopbanner': 'htmlDemoModal',
		'product-gallery': 'htmlDemoModal',
		'product-slider': 'htmlDemoModal',
		'product-tab': 'htmlDemoModal',
		'product-accordion': 'htmlDemoModal'
	};
	return modalIds[moduleType] || 'htmlDemoModal';
}

/**
 * Close modal when clicking outside the modal content
 */
document.addEventListener('click', function(event) {
	if (event.target.classList.contains('shopglut-template-modal-modal-overlay') ||
		event.target.id === 'htmlDemoModal' ||
		(event.target.classList.contains('shopglut-template-modal-image-modal') && event.target.id === 'htmlDemoModal')) {
		const moduleTypes = ['single-product', 'cart', 'order-complete', 'account', 'product-swatches', 'product-comparison', 'product-quickview', 'product-shopbanner', 'product-gallery', 'product-slider', 'product-tab', 'product-accordion'];
		moduleTypes.forEach(function(moduleType) {
			const modalId = getModalId(moduleType);
			const modal = document.getElementById(modalId);
			if (modal && modal.style.display === 'flex') {
				closeHtmlDemoModal(moduleType);
			}
		});
	}
});

/**
 * Close modal with Escape key
 */
document.addEventListener('keydown', function(event) {
	if (event.key === 'Escape') {
		const moduleTypes = ['single-product', 'cart', 'order-complete', 'account', 'product-swatches', 'product-comparison', 'product-quickview', 'product-shopbanner', 'product-gallery', 'product-slider', 'product-tab', 'product-accordion'];
		moduleTypes.forEach(function(moduleType) {
			const modalId = getModalId(moduleType);
			const modal = document.getElementById(modalId);
			if (modal && modal.style.display === 'flex') {
				closeHtmlDemoModal(moduleType);
			}
		});
	}
});

// Legacy function names for backward compatibility
function openCartHtmlDemoModal(templateId) {
	openHtmlDemoModal(templateId, 'cart');
}

function closeCartHtmlDemoModal() {
	closeHtmlDemoModal('cart');
}

function openOrderCompleteHtmlDemoModal(templateId) {
	openHtmlDemoModal(templateId, 'order-complete');
}

function closeOrderCompleteHtmlDemoModal() {
	closeHtmlDemoModal('order-complete');
}

function openAccountPageHtmlDemoModal(templateId) {
	openHtmlDemoModal(templateId, 'account');
}

function closeAccountPageHtmlDemoModal() {
	closeHtmlDemoModal('account');
}

function openShopBannerHtmlDemoModal(templateId) {
	openHtmlDemoModal(templateId, 'product-shopbanner');
}

function closeShopBannerHtmlDemoModal() {
	closeHtmlDemoModal('product-shopbanner');
}

/**
 * Initialize WooCommerce tabs for demo content
 * Called after modal content loads via AJAX
 */
function initializeDemoTabs() {
	// Find all WooCommerce tabs containers in the modal
	const tabsContainers = document.querySelectorAll('.woocommerce-tabs');

	tabsContainers.forEach(function(container) {
		const tabs = container.querySelectorAll('.wc-tabs li');
		const panels = container.querySelectorAll('.woocommerce-Tabs-panel');

		// Set first tab as active by default if none are active
		if (tabs.length > 0 && !container.querySelector('.wc-tabs li.active')) {
			tabs[0].classList.add('active');
			if (panels.length > 0) {
				panels[0].classList.add('active');
			}
		}
	});
}
