/**
 * Shop / Product Archive JavaScript
 *
 */


// ── Toast Helper ──
if (typeof window.shopnex_show_toast === 'undefined') {
    window.shopnex_show_toast = function(message) {
        if (typeof window.shopnexToast === 'function') {
            shopnexToast(message, 'success');
        } else {
            // Fallback toast
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px 20px;font-size:14px;font-weight:500;color:#111827;box-shadow:0 10px 25px rgba(0,0,0,0.1);transform:translateY(100px);opacity:0;transition:all 0.4s cubic-bezier(0.23,1,0.32,1);';
            toast.textContent = message;
            document.body.appendChild(toast);
            requestAnimationFrame(function() {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            });
            setTimeout(function() {
                toast.style.transform = 'translateY(100px)';
                toast.style.opacity = '0';
                setTimeout(function() { toast.remove(); }, 400);
            }, 3000);
        }
    };
}


// ── Wishlist Toggle ──
window.shopnexToggleWishlist = function(btn, productId) {
    btn.classList.toggle('active');

    var isActive = btn.classList.contains('active');

    // Update SVG fill
    var svg = btn.querySelector('svg');
    if (svg) {
        if (isActive) {
            svg.setAttribute('fill', 'currentColor');
        } else {
            svg.setAttribute('fill', 'none');
        }
    }

    if (typeof shopnex_show_toast === 'function') {
        shopnex_show_toast(isActive ? 'Added to wishlist ❤️' : 'Removed from wishlist');
    }
};


// ── Quick Add to Cart ──
window.quickAddToCart = function(productId, btnElement) {
    var quickBtn = btnElement || document.querySelector('.quick-view-btn[data-product-id="' + productId + '"]');

    // Get product name for feedback
    var productName = 'Product';
    if (quickBtn) {
        var productCard = quickBtn.closest('.product-card, .product, .type-product');
        if (productCard) {
            var titleElement = productCard.querySelector('.product-card-name, h2, h3, .woocommerce-loop-product__title');
            if (titleElement) {
                productName = titleElement.textContent.trim();
            }
        }
    }

    // Show loading state
    if (quickBtn) {
        quickBtn.innerHTML = '<svg style="animation:spin 1s linear infinite" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>';
        quickBtn.disabled = true;
        quickBtn.style.cursor = 'not-allowed';
    }

    // Check if shopnex_wc_params is available
    if (typeof shopnex_wc_params === 'undefined') {
        if (quickBtn) {
            quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
            quickBtn.disabled = false;
            quickBtn.style.cursor = '';
        }
        return;
    }

    // Build AJAX URL
    var ajaxUrl;
    if (shopnex_wc_params.wc_ajax_url && shopnex_wc_params.wc_ajax_url !== '') {
        ajaxUrl = shopnex_wc_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart');
    } else if (shopnex_wc_params.ajax_url) {
        ajaxUrl = shopnex_wc_params.ajax_url;
    } else {
        ajaxUrl = window.location.origin + window.location.pathname + '?wc-ajax=add_to_cart';
    }

    // Use WooCommerce AJAX to add product to cart
    if (typeof jQuery !== 'undefined') {
        jQuery.ajax({
            type: 'POST',
            url: ajaxUrl,
            data: {
                product_id: productId,
                quantity: 1
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.error) {
                    // Product cannot be added (e.g., variable product)
                    if (response.product_url) {
                        window.location.href = response.product_url;
                    } else {
                        if (quickBtn) {
                            quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
                            quickBtn.disabled = false;
                            quickBtn.style.cursor = '';
                        }
                    }
                } else if (response && (response.fragments || response.cart_hash !== undefined)) {
                    // Product added successfully
                    if (quickBtn) {
                        quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
                        quickBtn.classList.add('quick-view-added');
                        quickBtn.disabled = true;
                        quickBtn.style.cursor = 'not-allowed';
                    }

                    // Update cart fragments if available
                    if (response.fragments) {
                        shopnexUpdateCartFromFragments(response.fragments);
                    }

                    // Trigger WooCommerce added_to_cart event
                    jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, quickBtn]);

                    // Show toast if available
                    if (typeof shopnex_show_toast === 'function') {
                        shopnex_show_toast(productName + ' added to cart!');
                    }
                } else {
                    // Unknown response format - try to refresh fragments anyway
                    shopnexRefreshCartFragments();

                    if (quickBtn) {
                        quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
                        quickBtn.classList.add('quick-view-added');
                        quickBtn.disabled = true;
                        quickBtn.style.cursor = 'not-allowed';
                    }
                }
            },
            error: function(xhr, status, error) {

                // Try to parse response anyway
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response && response.fragments) {
                        if (quickBtn) {
                            quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
                            quickBtn.classList.add('quick-view-added');
                            quickBtn.disabled = true;
                            quickBtn.style.cursor = 'not-allowed';
                        }
                        shopnexUpdateCartFromFragments(response.fragments);
                        return;
                    }
                } catch (e) {
                    // Could not parse response as JSON
                }

                // Reset button on error
                if (quickBtn) {
                    quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
                    quickBtn.disabled = false;
                    quickBtn.style.cursor = '';
                    quickBtn.classList.remove('quick-view-added');
                }
            }
        });
    } else {
        if (quickBtn) {
            quickBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>';
            quickBtn.disabled = false;
            quickBtn.style.cursor = '';
            quickBtn.classList.remove('quick-view-added');
        }
    }
};


// ── Helper: Update cart from fragments ──
function shopnexUpdateCartFromFragments(fragments) {
    if (!fragments) return false;

    var selectors = [
        'a.cart-action', '.cart-action', '.cart-contents', '.cart-count', 'span.cart-count',
        'div.cart-drawer-items', 'div.drawer-footer', 'span.cart-count-label'
    ];
    var updated = false;

    for (var i = 0; i < selectors.length; i++) {
        if (fragments[selectors[i]]) {
            jQuery(selectors[i]).replaceWith(fragments[selectors[i]]);
            updated = true;
        }
    }

    // Force show cart count
    var cartCount = jQuery('.cart-action .cart-count, a.cart-action .cart-count, .cart-count, span.cart-count');
    if (cartCount.length > 0) {
        cartCount.show();
    }

    // Show/hide drawer footer based on cart content
    var drawerFooter = jQuery('#cartDrawer .drawer-footer');
    if (drawerFooter.length > 0 && jQuery.trim(drawerFooter.html()) !== '') {
        drawerFooter.show();
    }

    return updated;
}


// ── Helper: Refresh cart fragments ──
function shopnexRefreshCartFragments() {
    if (typeof jQuery === 'undefined' || typeof shopnex_wc_params === 'undefined') {
        return;
    }

    var fragmentsUrl;
    if (shopnex_wc_params.wc_ajax_url && shopnex_wc_params.wc_ajax_url !== '') {
        fragmentsUrl = shopnex_wc_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments');
    } else if (shopnex_wc_params.ajax_url) {
        fragmentsUrl = shopnex_wc_params.ajax_url + '?action=woocommerce_get_refreshed_fragments';
    } else {
        fragmentsUrl = window.location.origin + window.location.pathname + '?wc-ajax=get_refreshed_fragments';
    }

    jQuery.ajax({
        type: 'POST',
        url: fragmentsUrl,
        success: function(fragments) {
            shopnexUpdateCartFromFragments(fragments);
        },
        error: function(xhr, status, error) {
            // Silently handle fragment refresh errors
        }
    });
}


// ── Remove Cart Item ──
window.shopnexRemoveCartItem = function(cartItemKey, btnElement) {
    if (!cartItemKey) return;

    var cartItem = btnElement ? btnElement.closest('.cart-drawer-item') : null;

    // Show loading state
    if (btnElement) {
        btnElement.disabled = true;
        btnElement.style.opacity = '0.5';
    }
    if (cartItem) {
        cartItem.style.opacity = '0.5';
    }

    if (typeof jQuery === 'undefined' || typeof shopnex_wc_params === 'undefined') {
        // Fallback: navigate to cart page
        window.location.href = wc_cart_url || '/cart/';
        return;
    }

    var removeUrl;
    if (shopnex_wc_params.wc_ajax_url && shopnex_wc_params.wc_ajax_url !== '') {
        removeUrl = shopnex_wc_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart');
    } else if (shopnex_wc_params.ajax_url) {
        removeUrl = shopnex_wc_params.ajax_url;
    } else {
        removeUrl = window.location.origin + window.location.pathname + '?wc-ajax=remove_from_cart';
    }

    jQuery.ajax({
        type: 'POST',
        url: removeUrl,
        data: {
            cart_item_key: cartItemKey
        },
        dataType: 'json',
        success: function(response) {
            if (response && response.fragments) {
                shopnexUpdateCartFromFragments(response.fragments);

                // Trigger WooCommerce event
                jQuery(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash, btnElement]);

                if (typeof shopnex_show_toast === 'function') {
                    shopnex_show_toast('Item removed from cart');
                }
            } else {
                // Fallback: reload page
                window.location.reload();
            }
        },
        error: function() {
            // Reset state
            if (btnElement) {
                btnElement.disabled = false;
                btnElement.style.opacity = '';
            }
            if (cartItem) {
                cartItem.style.opacity = '';
            }
        }
    });
};


// ── Initialize on DOM Ready ──
jQuery(document).ready(function($) {
    // ── Card fade-in animation with stagger ──
    function animateCards() {
        var cards = document.querySelectorAll('.product-card');
        cards.forEach(function(card, index) {
            card.style.animationDelay = (index * 0.06) + 's';
        });
    }
    animateCards();

    // ── Lazy image fade ──
    document.querySelectorAll('.product-card-img-link img[loading="lazy"]').forEach(function(img) {
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.5s ease';
        img.addEventListener('load', function() { img.style.opacity = '1'; });
        if (img.complete) img.style.opacity = '1';
    });

    // ── Cart item remove button delegation ──
    $(document).on('click', '.cart-item-remove', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var key = $(this).data('key');
        if (key) {
            shopnexRemoveCartItem(key, this);
        }
    });

    // Check cart on page load and update buttons for products already in cart
    if (typeof shopnex_wc_params !== 'undefined') {
        // Get current cart contents
        $.ajax({
            type: 'POST',
            url: shopnex_wc_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
            success: function(fragments) {
                if (fragments && fragments['a.cart-action']) {
                    $('.cart-action').replaceWith(fragments['a.cart-action']);
                }
            }
        });

        // Get cart data to update buttons for products already in cart
        $.ajax({
            type: 'POST',
            url: shopnex_wc_params.ajax_url,
            data: {
                action: 'shopnex_get_cart',
                nonce: shopnex_wc_params.cart_data_nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    var cartProductIds = response.data.cart_product_ids || [];

                    if (cartProductIds.length > 0) {
                        $('.product-card-actions').each(function() {
                            var card = $(this).closest('.product-card');
                            if (card.length) {
                                var productId = card.data('product-id');
                                if (productId && cartProductIds.includes(parseInt(productId))) {
                                    // Find the add to cart button (first button in actions)
                                    var btn = $(this).find('button').first();
                                    if (btn.length && !btn.hasClass('product-card-wishlist') && !btn.hasClass('product-card-quick-view-icon')) {
                                        btn.html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>');
                                        btn.addClass('quick-view-added');
                                        btn.prop('disabled', true);
                                        btn.css('cursor', 'not-allowed');
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    }

    // WooCommerce Cart Fragment Refresh events
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
        // Cart has been updated
    });

    $(document.body).on('removed_from_cart', function(event, fragments, cart_hash, button) {
        // Cart has been updated
    });

    $(document.body).on('wc_fragments_refreshed', function() {
        // Cart fragments have been refreshed
    });

    // ── Delegated event listeners ──

    // Category tabs (data-set-tab)
    $(document).on('click', '[data-set-tab]', function() {
        $(this).siblings('.cat-tab').removeClass('active');
        $(this).addClass('active');
    });

    // Open filter drawer (data-open-drawer)
    $(document).on('click', '[data-open-drawer]', function() {
        if (typeof window.shopnexOpenFilterDrawer === 'function') {
            shopnexOpenFilterDrawer();
        }
    });

    // Toggle active class on filter pills (data-toggle-active)
    $(document).on('click', '[data-toggle-active]', function() {
        this.classList.toggle('active');
    });

    // Grid view toggle (data-set-view)
    $(document).on('click', '[data-set-view]', function() {
        var cols = parseInt($(this).attr('data-set-view')) || 4;
        $(this).siblings('.view-btn').removeClass('active');
        $(this).addClass('active');
        var grid = document.querySelector('.products, .product-grid, .shopnex-products-grid');
        if (grid) {
            grid.style.gridTemplateColumns = 'repeat(' + cols + ', 1fr)';
        }
    });

    // Quick add to cart (data-quick-add-to-cart)
    $(document).on('click', '[data-quick-add-to-cart]', function() {
        var productId = parseInt($(this).attr('data-quick-add-to-cart'));
        if (productId && typeof window.quickAddToCart === 'function') {
            quickAddToCart(productId, this);
        }
    });
});
