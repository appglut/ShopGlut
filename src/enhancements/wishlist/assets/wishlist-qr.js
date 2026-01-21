/**
 * Shopglut Wishlist QR Code Functionality - Simplified Version
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        initQRFeatures();
    });
    
    function initQRFeatures() {
        // QR Share button click
        $(document).on('click', '.shopglut-qr-share-btn', function(e) {
            e.preventDefault();
            const listName = $(this).data('list-name') || '';
            generateQRCode(listName);
        });
        
        // Modal close functionality
        $(document).on('click', '.shopglut-modal-close', function(e) {
            e.preventDefault();
            closeQRModal();
        });
        
        // Click outside modal to close
        $(document).on('click', '.shopglut-modal', function(e) {
            if (e.target === this) {
                closeQRModal();
            }
        });
        
        // Copy link functionality
        $(document).on('click', '.copy-link-btn', function(e) {
            e.preventDefault();
            copyShareLink();
        });
        
        // Download QR functionality
        $(document).on('click', '.download-wishlist-qr-btn', function(e) {
            e.preventDefault();
            downloadQRCode();
        });
        
        // Handle ESC key to close modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQRModal();
            }
        });
        
        // Check if this is a shared wishlist page
        checkSharedWishlistPage();
    }
    
    function generateQRCode(listName = '') {
        showQRModal();
        showQRLoading();
        
        $.ajax({
            url: wishlist_qr_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'shopglut_generate_qr_code',
                nonce: wishlist_qr_ajax.nonce,
                list_name: listName
            },
            success: function(response) {
                if (response.success) {
                    displayQRCodeWithFallback(response.data);
                } else {
                    showQRError('Failed to generate QR code: ' + (response.data || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                // If server fails, try client-side generation
                generateClientSideQR(listName);
            }
        });
    }
    
    function displayQRCodeWithFallback(qrData) {
        // Try to load the QR code image with error handling
        const img = new Image();
        img.onload = function() {
            displayQRCode(qrData);
        };
        img.onerror = function() {
            tryAlternativeQRServices(qrData);
        };
        img.src = qrData.qr_url;
    }
    
    function tryAlternativeQRServices(qrData) {
        const alternativeServices = [
            'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(qrData.wishlist_url),
            'https://quickchart.io/qr?text=' + encodeURIComponent(qrData.wishlist_url) + '&size=200',
            'https://qrcode.tec-it.com/API/QRCode?data=' + encodeURIComponent(qrData.wishlist_url) + '&size=200'
        ];
        
        tryQRService(alternativeServices, 0, qrData);
    }
    
    function tryQRService(services, index, qrData) {
        if (index >= services.length) {
            // All services failed, use client-side generation
            generateClientSideQRFromData(qrData);
            return;
        }
        
        const img = new Image();
        img.onload = function() {
            qrData.qr_url = services[index];
            displayQRCode(qrData);
        };
        img.onerror = function() {
            // Try next service
            tryQRService(services, index + 1, qrData);
        };
        img.src = services[index];
    }
    
    function generateClientSideQR(listName = '') {
        // Generate client-side QR code using a simple library or canvas
        showQRLoading();
        
        // Create a basic wishlist URL for client-side generation
        const baseUrl = window.location.origin + window.location.pathname;
        const shareUrl = baseUrl + '?view_shared=1&data=' + btoa(JSON.stringify({
            user_id: shopglut_ajax.user_id || 'guest',
            list_name: listName,
            timestamp: Date.now()
        }));
        
        const qrData = {
            qr_url: '',
            wishlist_url: shareUrl,
            user_id: shopglut_ajax.user_id || 'guest',
            list_name: listName
        };
        
        generateClientSideQRFromData(qrData);
    }
    
    function generateClientSideQRFromData(qrData) {
        // Try to use a simple client-side QR generator
        if (typeof QRCode !== 'undefined') {
            // If QRCode library is available
            generateWithQRCodeLibrary(qrData);
        } else {
            // Load QR code library dynamically
            loadQRCodeLibrary().then(() => {
                generateWithQRCodeLibrary(qrData);
            }).catch(() => {
                // Fallback to a simple visual representation
                generateSimpleQRFallback(qrData);
            });
        }
    }
    
    function loadQRCodeLibrary() {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }
    
    function generateWithQRCodeLibrary(qrData) {
        const canvas = document.createElement('canvas');
        
        QRCode.toCanvas(canvas, qrData.wishlist_url, {
            width: 200,
            height: 200,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function(error) {
            if (error) {
                console.error('QR generation error:', error);
                generateSimpleQRFallback(qrData);
            } else {
                qrData.qr_url = canvas.toDataURL();
                displayQRCode(qrData);
            }
        });
    }
    
    function generateSimpleQRFallback(qrData) {
        // Create a simple fallback display
        const fallbackHtml = `
            <div class="qr-fallback">
                <div class="qr-placeholder">
                    <i class="fas fa-qrcode"></i>
                    <p>QR Code Generation Failed</p>
                </div>
                <div class="fallback-options">
                    <p>Use the link below to share your wishlist:</p>
                    <button onclick="copyFallbackLink()" class="copy-fallback-btn">
                        <i class="fas fa-copy"></i> Copy Link
                    </button>
                </div>
            </div>
        `;
        
        $('.qr-code-container').html(fallbackHtml);
        $('#share-url-input').val(qrData.wishlist_url);
        
        // Store for copy function
        window.fallbackUrl = qrData.wishlist_url;
    }
    
    function generateProductQR(productId) {
        const productUrl = window.location.origin + '/?p=' + productId;
        
        // Try multiple services for product QR
        const services = [
            'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(productUrl),
            'https://quickchart.io/qr?text=' + encodeURIComponent(productUrl) + '&size=200'
        ];
        
        showQRModal();
        showQRLoading();
        
        tryProductQRService(services, 0, {
            qr_url: '',
            wishlist_url: productUrl,
            product_id: productId
        });
    }
    
    function tryProductQRService(services, index, qrData) {
        if (index >= services.length) {
            // All services failed, use client-side
            if (typeof QRCode !== 'undefined') {
                generateWithQRCodeLibrary(qrData);
            } else {
                generateSimpleQRFallback(qrData);
            }
            return;
        }
        
        const img = new Image();
        img.onload = function() {
            qrData.qr_url = services[index];
            displayQRCode(qrData);
        };
        img.onerror = function() {
            tryProductQRService(services, index + 1, qrData);
        };
        img.src = services[index];
    }
    
    function displayQRCode(qrData) {
        const qrHtml = `
            <div class="qr-code-display">
                <img src="${qrData.qr_url}" alt="QR Code" />
                <p class="qr-instruction">Scan this QR code to view the wishlist</p>
            </div>
        `;
        
        $('.qr-code-container').html(qrHtml);
        $('#share-url-input').val(qrData.wishlist_url);
        
        // Store data for download
        $('.qr-code-container img').data('qr-data', qrData);
    }
    
    function showQRLoading() {
        const loadingHtml = `
            <div class="qr-loading">
                <div class="spinner"></div>
                <p>Generating QR code...</p>
            </div>
        `;
        $('.qr-code-container').html(loadingHtml);
    }
    
    function showQRError(message) {
        const errorHtml = `
            <div class="qr-error">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
                <button onclick="location.reload()" class="retry-btn">Try Again</button>
            </div>
        `;
        $('.qr-code-container').html(errorHtml);
    }
    
    function showQRModal() {
        $('#shopglut-qr-modal').fadeIn(300);
        $('body').addClass('shopglut-modal-open');
    }
    
    function closeQRModal() {
        $('#shopglut-qr-modal').fadeOut(300);
        $('body').removeClass('shopglut-modal-open');
    }
    
    function copyShareLink() {
        const url = $('#share-url-input').val();
        const button = $('.copy-link-btn');
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(function() {
                showCopySuccess(button);
            }).catch(function() {
                fallbackCopyToClipboard(url, button);
            });
        } else {
            fallbackCopyToClipboard(url, button);
        }
    }
    
    function fallbackCopyToClipboard(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showCopySuccess(button);
        } catch (err) {
            console.error('Copy failed:', err);
            showCopyError(button);
        }
        
        document.body.removeChild(textArea);
    }
    
    function showCopySuccess(button) {
        const originalHtml = button.html();
        button.html('<i class="fas fa-check"></i> Copied!').addClass('copied');
        
        setTimeout(function() {
            button.html(originalHtml).removeClass('copied');
        }, 2000);
    }
    
    function showCopyError(button) {
        const originalHtml = button.html();
        button.html('<i class="fas fa-times"></i> Failed').addClass('copy-error');
        
        setTimeout(function() {
            button.html(originalHtml).removeClass('copy-error');
        }, 2000);
    }
    
    function downloadQRCode() {
        const qrImage = $('.qr-code-container img');
        const qrData = qrImage.data('qr-data');
        
        if (!qrData || !qrImage.length) {
            alert('No QR code available for download.');
            return;
        }
        
        // Create a temporary link to download the image
        const link = document.createElement('a');
        link.href = qrData.qr_url;
        link.download = 'wishlist-qr-code.png';
        
        // Try to download directly first
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Fallback: If direct download doesn't work, use canvas method
        setTimeout(function() {
            downloadQRViaCanvas(qrData);
        }, 500);
    }
    
    function downloadQRViaCanvas(qrData) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            
            // White background
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Draw QR code
            ctx.drawImage(img, 0, 0);
            
            // Convert to blob and download
            canvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'wishlist-qr-code.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }, 'image/png');
        };
        
        img.onerror = function() {
            alert('Failed to download QR code. Please try again.');
        };
        
        img.src = qrData.qr_url;
    }
    
    function checkSharedWishlistPage() {
        // Check if current page is a shared wishlist
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view_shared') === '1') {
            addSharedWishlistStyles();
            addSharedWishlistNotice();
        }
    }
    
    function addSharedWishlistNotice() {
        // Add a notice that this is a shared wishlist
        const notice = `
            <div class="shared-wishlist-notice">
                <i class="fas fa-share-alt"></i>
                <span>You're viewing a shared wishlist</span>
                <button onclick="createMyOwnWishlist()" class="create-own-btn">Create My Own</button>
            </div>
        `;
        
        $('.shopglut-shared-wishlist').prepend(notice);
    }
    
    function addSharedWishlistStyles() {
        // Add additional CSS for shared wishlist functionality
        const additionalStyles = `
            <style>
            .shared-wishlist-notice {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .shared-wishlist-notice i {
                font-size: 18px;
                margin-right: 10px;
            }
            
            .create-own-btn {
                background: rgba(255,255,255,0.2);
                border: 1px solid rgba(255,255,255,0.3);
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.3s ease;
            }
            
            .create-own-btn:hover {
                background: rgba(255,255,255,0.3);
                transform: translateY(-2px);
            }
            
            .qr-loading {
                text-align: center;
                padding: 40px 20px;
                color: #6c757d;
            }
            
            .spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #007cba;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 15px;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .qr-error {
                text-align: center;
                padding: 40px 20px;
                color: #dc3545;
            }
            
            .qr-error i {
                font-size: 48px;
                margin-bottom: 15px;
                opacity: 0.7;
            }
            
            .retry-btn {
                background: #007cba;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 15px;
                transition: background 0.3s ease;
            }
            
            .retry-btn:hover {
                background: #005a87;
            }
            
            .qr-code-display {
                text-align: center;
            }
            
            .qr-code-display img {
                max-width: 200px;
                height: auto;
                border: 2px solid #e9ecef;
                border-radius: 8px;
                padding: 10px;
                background: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .qr-instruction {
                margin-top: 15px;
                color: #6c757d;
                font-size: 14px;
                font-style: italic;
            }
            
            .copy-link-btn.copy-error {
                background: #dc3545;
            }
            
            .copy-link-btn.copy-error:hover {
                background: #c82333;
            }
            
            @media (max-width: 480px) {
                .shopglut-modal-content {
                    width: 95%;
                    margin: 10% auto;
                }
                
                .shopglut-modal-body {
                    padding: 20px;
                }
                
                .shared-wishlist-notice {
                    flex-direction: column;
                    gap: 10px;
                    text-align: center;
                }
            }
            </style>
        `;
        
        $('head').append(additionalStyles);
    }
    
    // Global functions that can be called from PHP
    window.generateProductQR = generateProductQR;
    
    window.createMyOwnWishlist = function() {
        // Redirect to main wishlist page
        const wishlistUrl = shopglut_ajax.wishlist_url || '/wishlist/';
        window.location.href = wishlistUrl;
    };
    
    window.copyFallbackLink = function() {
        const url = window.fallbackUrl || $('#share-url-input').val();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                $('.copy-fallback-btn').html('<i class="fas fa-check"></i> Copied!').addClass('copied');
                setTimeout(() => {
                    $('.copy-fallback-btn').html('<i class="fas fa-copy"></i> Copy Link').removeClass('copied');
                }, 2000);
            });
        } else {
            alert('Copy this link: ' + url);
        }
    };
    
    // Share functionality for individual products
    window.shareProduct = function(productId) {
        const productUrl = window.location.origin + '/?p=' + productId;
        
        if (navigator.share) {
            navigator.share({
                title: 'Check out this product',
                url: productUrl
            }).catch(console.error);
        } else {
            // Fallback: copy to clipboard
            if (navigator.clipboard) {
                navigator.clipboard.writeText(productUrl).then(function() {
                    alert('Product link copied to clipboard!');
                });
            } else {
                alert('Product URL: ' + productUrl);
            }
        }
    };
    
    // Auto-generate QR for current wishlist if needed
    window.autoGenerateWishlistQR = function() {
        if ($('.shopglut-shared-wishlist').length > 0) {
            // This is already a shared view, don't auto-generate
            return;
        }
        
        generateQRCode();
    };
    
    // Test QR services availability
    window.testQRServices = function() {
        const testData = 'https://example.com/test';
        const services = [
            'https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=' + encodeURIComponent(testData),
            'https://quickchart.io/qr?text=' + encodeURIComponent(testData) + '&size=50'
        ];
        
        services.forEach((service, index) => {
            const img = new Image();
            img.src = service;
        });
    };
    
})(jQuery);

// CSS for spinner animation and additional styles
document.addEventListener('DOMContentLoaded', function() {
    // Add base styles if not already present

    
    // Add test button in development mode
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
        const testButton = document.createElement('div');
        testButton.className = 'qr-service-test';
        testButton.innerHTML = 'Test QR Services';
        testButton.style.display = 'block';
        testButton.onclick = window.testQRServices;
        document.body.appendChild(testButton);
    }
});