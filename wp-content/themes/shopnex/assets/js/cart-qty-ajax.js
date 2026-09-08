/**
 * Cart Page
 *
 * @package shopnex
 */

(function () {
    'use strict';

    // if we're not on the cart page.
    if (!document.body || !document.body.classList.contains('woocommerce-cart')) {
        return;
    }

    var ajaxUrl = (typeof shopnexCartQty !== 'undefined') ? shopnexCartQty.ajaxUrl : '';
    var nonce   = (typeof shopnexCartQty !== 'undefined') ? shopnexCartQty.nonce : '';

    if (!ajaxUrl || !nonce) {
        return;
    }

    // Debounce timer.
    var debounceTimers = {};

    /**
     * Update a single cart item's quantity via AJAX.
     *
     * @param {string}  cartItemKey The cart item key.
     * @param {number}  quantity    The new quantity.
     * @param {Element} inputEl     The quantity input element.
     */
    function updateCartItem(cartItemKey, quantity, inputEl) {
        var cartItemRow = inputEl.closest('.cart-item, .woocommerce-cart-form__cart-item');
        if (!cartItemRow) {
            return;
        }

        // Add loading state to cart item row.
        cartItemRow.classList.add('shopnex-cart-updating');

        // Add loading state to order summary.
        var cartSummary = document.querySelector('.cart-summary');
        if (cartSummary) {
            cartSummary.classList.add('shopnex-summary-updating');
        }

        var formData = new FormData();
        formData.append('action', 'shopnex_update_cart_qty');
        formData.append('nonce', nonce);
        formData.append('cart_item_key', cartItemKey);
        formData.append('quantity', quantity);

        fetch(ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData,
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                refreshCartFragments(data.data);
            } else {
                // On error (e.g. item removed), reload the page.
                window.location.reload();
            }
        })
        .catch(function () {
            // Fallback: reload the page.
            window.location.reload();
        })
        .finally(function () {
            cartItemRow.classList.remove('shopnex-cart-updating');
            if (cartSummary) {
                cartSummary.classList.remove('shopnex-summary-updating');
            }
        });
    }

    /**
     * Refresh the cart page DOM with the updated data from the server.
     *
     * @param {Object} data Updated cart data from the AJAX response.
     */
    function refreshCartFragments(data) {
        // Update line total for the specific cart item in the main cart table.
        if (data.item_key && data.line_total_html) {
            var row = document.querySelector(
                '.cart-item[data-cart-item-key="' + CSS.escape(data.item_key) + '"], ' +
                '.woocommerce-cart-form__cart-item[data-cart-item-key="' + CSS.escape(data.item_key) + '"]'
            );
            if (row) {
                var totalCell = row.querySelector('.item-price');
                if (totalCell) {
                    totalCell.innerHTML = data.line_total_html;
                }
            }
        }

        // Update the cart item count badge.
        if (data.cart_contents_count !== undefined) {
            var countBadge = document.querySelector('.item-count-badge');
            if (countBadge) {
                var singular = shopnexCartQty.i18nItem || '%s Item';
                var plural   = shopnexCartQty.i18nItems || '%s Items';
                var template = (data.cart_contents_count === 1) ? singular : plural;
                countBadge.textContent = template.replace('%s', data.cart_contents_count);
            }
        }

        // Update order summary — products list.
        if (data.products_html !== undefined) {
            var productsList = document.querySelector('.summary-products');
            if (productsList) {
                productsList.innerHTML = data.products_html;
            }
        }

        // Update order summary — subtotal.
        if (data.subtotal_html) {
            var subtotalRow = document.querySelector('.summary-row.summary-subtotal .summary-value');
            if (subtotalRow) {
                subtotalRow.innerHTML = data.subtotal_html;
            }
        }

        // Update order summary — coupons.
        if (data.coupon_html !== undefined) {
            var existingCoupons = document.querySelectorAll('.summary-row.discount-line');
            existingCoupons.forEach(function (el) { el.remove(); });

            if (data.coupon_html) {
                var subtotalLine = document.querySelector('.summary-row.summary-subtotal');
                if (subtotalLine) {
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.coupon_html;
                    while (tempDiv.firstChild) {
                        subtotalLine.parentNode.insertBefore(tempDiv.firstChild, subtotalLine.nextSibling);
                    }
                }
            }
        }

        // Update order summary — shipping.
        if (data.shipping_html !== undefined) {
            var shippingVal = document.getElementById('shippingVal');
            if (shippingVal) {
                shippingVal.innerHTML = data.shipping_html;
            }
        }

        // Update order summary — total.
        if (data.total_html) {
            var totalLine = document.querySelector('.summary-row.total .summary-value');
            if (totalLine) {
                totalLine.innerHTML = data.total_html;
            }
        }

        // Update tax.
        if (data.tax_html !== undefined) {
            var taxEl = document.querySelector('.summary-tax');
            if (taxEl) {
                taxEl.innerHTML = data.tax_html;
            }
        }

        // If the cart is now empty, reload to show the empty cart template.
        if (data.cart_is_empty) {
            window.location.reload();
        }
    }

    /**
     * Handle quantity input changes with debouncing.
     */
    function initQuantityListeners() {
        var cartForm = document.querySelector('.woocommerce-cart-form');
        if (!cartForm) {
            return;
        }

        // Listen for both 'input' (fires on every keystroke) and 'change' (fires on blur).
        cartForm.addEventListener('input', handleQtyChange);
        cartForm.addEventListener('change', handleQtyChange);

        // Also listen for +/- button clicks (theme uses .qty-btn.qty-minus / .qty-btn.qty-plus).
        cartForm.addEventListener('click', handleQtyButtonClick);
    }

    /**
     * Handle direct input changes.
     */
    function handleQtyChange(e) {
        var input = e.target;
        if (!input.classList.contains('qty') && !input.classList.contains('input-text')) {
            return;
        }
        // Make sure it's inside a cart item row.
        var row = input.closest('.cart-item, .woocommerce-cart-form__cart-item');
        if (!row) {
            return;
        }

        var cartItemKey = row.getAttribute('data-cart-item-key');
        if (!cartItemKey) {
            return;
        }

        var quantity = parseInt(input.value, 10);
        if (isNaN(quantity) || quantity < 0) {
            return;
        }

        // Debounce: clear previous timer for this item.
        if (debounceTimers[cartItemKey]) {
            clearTimeout(debounceTimers[cartItemKey]);
        }

        debounceTimers[cartItemKey] = setTimeout(function () {
            updateCartItem(cartItemKey, quantity, input);
        }, 600);
    }

    /**
     * Handle +/- button clicks
     */
    function handleQtyButtonClick(e) {
        var btn = e.target.closest('.qty-btn, .plus, .minus, .quantity-button, .qty-minus, .qty-plus');
        if (!btn) {
            return;
        }

        var row = btn.closest('.cart-item, .woocommerce-cart-form__cart-item');
        if (!row) {
            return;
        }

        var cartItemKey = row.getAttribute('data-cart-item-key');
        if (!cartItemKey) {
            return;
        }

        var input = row.querySelector('.qty, input[type="number"]');
        if (!input) {
            return;
        }

        // Use a micro-delay to let the theme's button handler update the input value first.
        if (debounceTimers[cartItemKey]) {
            clearTimeout(debounceTimers[cartItemKey]);
        }

        debounceTimers[cartItemKey] = setTimeout(function () {
            var quantity = parseInt(input.value, 10);
            if (isNaN(quantity) || quantity < 0) {
                return;
            }
            updateCartItem(cartItemKey, quantity, input);
        }, 600);
    }

    // ─── double-confirmation on item removal ───
    function fixDoubleRemoveConfirmation() {
        document.addEventListener('click', function (e) {
            var removeBtn = e.target.closest('.remove-btn');
            if (!removeBtn) return;

            // Stop the event dead — no jQuery handler will ever see it.
            e.stopImmediatePropagation();
            e.preventDefault();

            var productName = '';
            var row = removeBtn.closest('.cart-item, .woocommerce-cart-form__cart-item');
            if (row) {
                var nameEl = row.querySelector('.item-name, .product-name a');
                if (nameEl) productName = nameEl.textContent.trim();
            }

            var msg = 'Are you sure you want to remove this item from your cart?';
            if (typeof shopnex_cart_ajax !== 'undefined' && shopnex_cart_ajax.i18n_remove) {
                msg = shopnex_cart_ajax.i18n_remove;
            }

            if (confirm(msg)) {
                // Navigate to the remove URL ourselves.
                var href = removeBtn.getAttribute('href');
                if (href) {
                    window.location.href = href;
                }
            }
        }, true); // ← capture phase: runs before ALL bubble-phase handlers
    }

    // Initialize on DOM ready.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initQuantityListeners();
            fixDoubleRemoveConfirmation();
        });
    } else {
        initQuantityListeners();
        fixDoubleRemoveConfirmation();
    }
})();
