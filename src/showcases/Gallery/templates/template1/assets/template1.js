/**
 * ShopGlut Gallery Template1 - Simple Grid Gallery JavaScript
 */

class ShopGlutGalleryTemplate1 {
	constructor() {
		this.init();
	}

	init() {
		this.bindEvents();
		this.setupKeyboardNavigation();
	}

	bindEvents() {
		// Prevent default lightbox link behavior and handle custom lightbox
		document.addEventListener('click', (e) => {
			if (e.target.closest('.gallery-lightbox-link')) {
				e.preventDefault();
				const link = e.target.closest('.gallery-lightbox-link');
				this.openLightbox(link);
			}

			if (e.target.closest('.lightbox-close')) {
				this.closeLightbox();
			}

			if (e.target.closest('.lightbox-prev')) {
				this.navigateLightbox('prev');
			}

			if (e.target.closest('.lightbox-next')) {
				this.navigateLightbox('next');
			}

			if (e.target.closest('.lightbox-overlay')) {
				this.closeLightbox();
			}
		});
	}

	openLightbox(link) {
		const gallery = link.closest('.shopglut-gallery-simple-grid');
		if (!gallery) return;

		const enableLightbox = gallery.dataset.enableLightbox === 'true';
		if (!enableLightbox) return;

		const modal = gallery.querySelector('.gallery-lightbox-modal');
		const lightboxImage = gallery.querySelector('.lightbox-image');
		const captionTitle = gallery.querySelector('.caption-title');
		const allItems = Array.from(gallery.querySelectorAll('.gallery-item'));
		const currentIndex = allItems.findIndex(item => item.contains(link));

		if (!modal || currentIndex === -1) return;

		// Set current index
		gallery.currentLightboxIndex = currentIndex;
		gallery.totalLightboxItems = allItems.length;

		// Set image source
		const imageSrc = link.getAttribute('href');
		const title = link.dataset.title || '';

		lightboxImage.src = imageSrc;
		captionTitle.textContent = title;

		// Show modal
		modal.style.display = 'flex';
		document.body.style.overflow = 'hidden';

		// Add loading state
		lightboxImage.style.opacity = '0';

		lightboxImage.onload = () => {
			lightboxImage.style.opacity = '1';
			lightboxImage.style.transition = 'opacity 0.3s ease';
		};

		lightboxImage.onerror = () => {
			lightboxImage.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDlWMTNNMTIgMTdWMTRNMTIgMjFWMThNMTIgN1Y0TTEyIDEzVjEwIiBzdHJva2U9IiM5OTk5OTkiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+Cjwvc3ZnPgo=';
			lightboxImage.style.opacity = '1';
		};

		this.updateNavigationButtons(gallery);
	}

	closeLightbox() {
		const modals = document.querySelectorAll('.gallery-lightbox-modal');
		modals.forEach(modal => {
			modal.style.display = 'none';
		});
		document.body.style.overflow = '';
	}

	navigateLightbox(direction) {
		const activeGallery = document.querySelector('.gallery-lightbox-modal[style*="flex"]')?.closest('.shopglut-gallery-simple-grid');
		if (!activeGallery) return;

		const allItems = Array.from(activeGallery.querySelectorAll('.gallery-item'));
		const currentIndex = activeGallery.currentLightboxIndex || 0;
		let newIndex;

		if (direction === 'prev') {
			newIndex = currentIndex === 0 ? allItems.length - 1 : currentIndex - 1;
		} else {
			newIndex = currentIndex === allItems.length - 1 ? 0 : currentIndex + 1;
		}

		const newItem = allItems[newIndex];
		if (newItem) {
			const newLink = newItem.querySelector('.gallery-lightbox-link');
			if (newLink) {
				const lightboxImage = activeGallery.querySelector('.lightbox-image');
				const captionTitle = activeGallery.querySelector('.caption-title');

				// Add transition effect
				lightboxImage.style.opacity = '0';
				lightboxImage.style.transform = 'scale(0.95)';

				setTimeout(() => {
					const imageSrc = newLink.getAttribute('href');
					const title = newLink.dataset.title || '';

					lightboxImage.src = imageSrc;
					captionTitle.textContent = title;

					lightboxImage.onload = () => {
						lightboxImage.style.opacity = '1';
						lightboxImage.style.transform = 'scale(1)';
						lightboxImage.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
					};
				}, 150);

				activeGallery.currentLightboxIndex = newIndex;
				this.updateNavigationButtons(activeGallery);
			}
		}
	}

	updateNavigationButtons(gallery) {
		const prevBtn = gallery.querySelector('.lightbox-prev');
		const nextBtn = gallery.querySelector('.lightbox-next');
		const totalItems = gallery.totalLightboxItems || 0;
		const currentIndex = gallery.currentLightboxIndex || 0;

		// Always show navigation buttons, but disable if only one item
		if (totalItems <= 1) {
			if (prevBtn) prevBtn.style.display = 'none';
			if (nextBtn) nextBtn.style.display = 'none';
		} else {
			if (prevBtn) prevBtn.style.display = 'flex';
			if (nextBtn) nextBtn.style.display = 'flex';
		}
	}

	setupKeyboardNavigation() {
		document.addEventListener('keydown', (e) => {
			const modal = document.querySelector('.gallery-lightbox-modal[style*="flex"]');
			if (!modal) return;

			switch (e.key) {
				case 'Escape':
					this.closeLightbox();
					break;
				case 'ArrowLeft':
					e.preventDefault();
					this.navigateLightbox('prev');
					break;
				case 'ArrowRight':
					e.preventDefault();
					this.navigateLightbox('next');
					break;
			}
		});
	}

	// Touch gesture support for mobile
	setupTouchGestures() {
		const modals = document.querySelectorAll('.gallery-lightbox-modal');
		modals.forEach(modal => {
			let startX = 0;
			let endX = 0;

			modal.addEventListener('touchstart', (e) => {
				startX = e.touches[0].clientX;
			});

			modal.addEventListener('touchend', (e) => {
				endX = e.changedTouches[0].clientX;
				const diff = startX - endX;

				if (Math.abs(diff) > 50) { // Minimum swipe distance
					if (diff > 0) {
						this.navigateLightbox('next'); // Swipe left, go to next
					} else {
						this.navigateLightbox('prev'); // Swipe right, go to prev
					}
				}
			});
		});
	}
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	new ShopGlutGalleryTemplate1();
});

// For dynamic content
if (typeof window !== 'undefined') {
	window.ShopGlutGalleryTemplate1 = ShopGlutGalleryTemplate1;
}