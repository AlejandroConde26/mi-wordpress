/**
 * Cart Page JavaScript
 *
 */

(function($) {
    'use strict';

    // Cart functionality
    const Cart = {
        init: function() {
            this.bindEvents();
            this.initQuantityControls();
            this.initRemoveButtons();
            this.initCouponForm();
        },

        bindEvents: function() {
            const self = this;

            // Listen for WooCommerce cart updates
            $(document.body).on('updated_cart_totals', function() {
                self.initQuantityControls();
                self.initRemoveButtons();
            });
        },

        // Quantity controls with circular +/- buttons
        initQuantityControls: function() {
            $('.cart-item').each(function() {
                const $item = $(this);
                const $quantityWrap = $item.find('.quantity-control .quantity');

                if (!$quantityWrap.length) return;

                const $input = $quantityWrap.find('input[type="number"]');

                // Remove existing buttons if any
                $quantityWrap.find('.qty-btn').remove();

                // Wrap input if not already wrapped
                if (!$quantityWrap.find('.qty-wrapper').length) {
                    $input.wrap('<div class="qty-wrapper"></div>');
                }

                const $wrapper = $quantityWrap.find('.qty-wrapper');

                // Add minus button
                const $minusBtn = $('<button type="button" class="qty-btn qty-minus">−</button>');
                $minusBtn.insertBefore($input);

                // Add plus button
                const $plusBtn = $('<button type="button" class="qty-btn qty-plus">+</button>');
                $plusBtn.insertAfter($input);

                // Handle minus click
                $minusBtn.on('click', function(e) {
                    e.preventDefault();
                    let val = parseInt($input.val()) || 0;
                    const min = parseInt($input.attr('min')) || 1;
                    if (val > min) {
                        $input.val(val - 1).trigger('change');
                    }
                });

                // Handle plus click
                $plusBtn.on('click', function(e) {
                    e.preventDefault();
                    let val = parseInt($input.val()) || 0;
                    const max = parseInt($input.attr('max')) || 999;
                    if (val < max) {
                        $input.val(val + 1).trigger('change');
                    }
                });
            });
        },

        // Remove item with animation
        initRemoveButtons: function() {
            $('.remove-btn').on('click', function(e) {
                const $btn = $(this);
                const $cartItem = $btn.closest('.cart-item');

                // Add removing animation
                $cartItem.addClass('removing');
            });
        },

        // Coupon form handling
        initCouponForm: function() {
            $('.promo-box').on('submit', function(e) {
                const $form = $(this);
                const $input = $form.find('.promo-input');
                const code = $input.val().trim();

                if (!code) {
                    e.preventDefault();
                    $input.addClass('error');
                    setTimeout(function() {
                        $input.removeClass('error');
                    }, 2000);
                    return false;
                }
            });
        }
    };

    // Initialize cart when DOM is ready
    $(document).ready(function() {
        if ($('body').hasClass('woocommerce-cart')) {
            Cart.init();
        }
    });

})(jQuery);
