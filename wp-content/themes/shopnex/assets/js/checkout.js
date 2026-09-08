/**
 * Checkout Page JavaScript
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {

        // ── Ship to Different Address Toggle ──
        const shipToDifferentCheckbox = document.getElementById('ship-to-different-address-checkbox');
        const shippingAddressFields = document.getElementById('shipping-address-fields');

        if (shipToDifferentCheckbox && shippingAddressFields) {
            shipToDifferentCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    shippingAddressFields.classList.add('show');
                    setTimeout(initShippingSelects, 50);
                } else {
                    shippingAddressFields.classList.remove('show');
                }
            });
        }

        // Initialize Select on shipping country and state selects
        function initShippingSelects() {
            if (typeof jQuery === 'undefined') return;

            var $shippingCountry = jQuery('#shipping_country');
            var $shippingState = jQuery('#shipping_state');

            if ($shippingCountry.hasClass('select2-hidden-accessible')) {
                $shippingCountry.select2('destroy');
            }
            if ($shippingState.hasClass('select2-hidden-accessible')) {
                $shippingState.select2('destroy');
            }

            if ($shippingCountry.length && $shippingCountry.hasClass('country_select')) {
                $shippingCountry.select2({
                    placeholder: $shippingCountry.data('placeholder') || 'Select a country / region…'
                });
            }

            if ($shippingState.length && $shippingState.hasClass('state_select')) {
                $shippingState.select2({
                    placeholder: $shippingState.data('placeholder') || 'Select an option…'
                });
            }

            jQuery(document.body).trigger('country_to_state_changed');
        }

        // ── Form Field Focus Effects ──
        const formInputs = document.querySelectorAll('.f-input, select, textarea');
        
        formInputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.closest('.form-field-custom')?.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.closest('.form-field-custom')?.classList.remove('focused');
                validateField(this);
            });

            input.addEventListener('input', function() {
                this.closest('.form-field-custom')?.classList.remove('error');
            });
        });

        // ── Field Validation ──
        function validateField(input) {
            const fieldWrapper = input.closest('.form-field-custom');
            if (!fieldWrapper) return;

            if (input.hasAttribute('required') && !input.value.trim()) {
                fieldWrapper.classList.add('error');
                return false;
            }

            if (input.type === 'email' && input.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    fieldWrapper.classList.add('error');
                    return false;
                }
            }

            if (input.type === 'tel' && input.value) {
                const phoneRegex = /^[\d\s\-\+\(\)]+$/;
                if (!phoneRegex.test(input.value)) {
                    fieldWrapper.classList.add('error');
                    return false;
                }
            }

            fieldWrapper.classList.remove('error');
            return true;
        }

        // ── Form Submission Validation ──
        const checkoutForm = document.querySelector('.checkout.woocommerce-checkout');
        
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredInputs = checkoutForm.querySelectorAll('.f-input[required], select[required], textarea[required]');

                requiredInputs.forEach(function(input) {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    const placeBtn = document.getElementById('place_order');
                    if (placeBtn) {
                        placeBtn.disabled = false;
                        placeBtn.classList.remove('loading');
                        const label = placeBtn.getAttribute('data-value');
                        if (label) {
                            placeBtn.textContent = label;
                        }
                    }

                    const firstError = checkoutForm.querySelector('.form-field-custom.error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    showNotification('Please fill in all required fields correctly.', 'error');
                }
            });
        }

        // ── Promo Code Handling ──
        const promoBtn = document.getElementById('apply_promo_btn');
        const promoInput = document.getElementById('promo_code_input');
        const promoMessage = document.getElementById('promoMessage');

        if (promoBtn && promoInput) {
            promoBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const code = promoInput.value.trim();

                if (!code) {
                    promoInput.classList.add('error');
                    promoInput.focus();
                    setTimeout(() => promoInput.classList.remove('error'), 500);
                    return;
                }

                promoBtn.disabled = true;
                promoBtn.textContent = 'Applying...';

                jQuery.post(
                    wc_checkout_params.ajax_url,
                    {
                        action: 'woocommerce_apply_coupon',
                        security: wc_checkout_params.apply_coupon_nonce,
                        coupon_code: code,
                        billing_email: (document.getElementById('billing_email') || {}).value || ''
                    },
                    function(response) {
                        promoBtn.disabled = false;
                        promoBtn.textContent = 'Apply';
                        if (response && response.indexOf('woocommerce-error') === -1) {
                            promoInput.value = '';
                            if (promoMessage) {
                                promoMessage.textContent = 'Coupon "' + code + '" applied!';
                                promoMessage.className = 's-promo-message success';
                            }
                            showNotification('Coupon code applied successfully!', 'success');
                            jQuery(document.body).trigger('applied_coupon_in_checkout', [code]);
                            jQuery(document.body).trigger('update_checkout', {update_shipping_method: false});
                        } else {
                            if (promoMessage) {
                                promoMessage.textContent = 'Invalid coupon code. Please try again.';
                                promoMessage.className = 's-promo-message error';
                            }
                            showNotification('Invalid coupon code. Please try again.', 'error');
                        }
                    }
                ).fail(function() {
                    promoBtn.disabled = false;
                    promoBtn.textContent = 'Apply';
                    if (promoMessage) {
                        promoMessage.textContent = 'An error occurred. Please try again.';
                        promoMessage.className = 's-promo-message error';
                    }
                    showNotification('An error occurred. Please try again.', 'error');
                });
            });

            promoInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    promoBtn.click();
                }
            });
        }

        // ── Payment Method Selection (Radio + Card pattern) ──
        function initPaymentOptions() {
            const radios = document.querySelectorAll('.payment-option .payment-radio-hidden');
            const descBox = document.getElementById('payment-desc');
            const paymentGrid = document.querySelector('.payment-grid');

            if (!radios.length) return;

            // Description box fade transition
            function updateDesc(radio) {
                if (!descBox) return;
                const text = radio.dataset.desc || '';
                if (!text) {
                    descBox.style.display = 'none';
                    return;
                }
                descBox.style.display = '';
                descBox.classList.add('fade');
                setTimeout(function() {
                    descBox.textContent = text;
                    descBox.classList.remove('fade');
                }, 120);
            }

            // Listen for radio changes
            radios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        updateDesc(this);
                    }
                });
            });

            // Arrow key navigation within the payment grid
            if (paymentGrid) {
                paymentGrid.addEventListener('keydown', function(e) {
                    var items = Array.from(radios);
                    var idx = items.findIndex(function(r) { return r.checked; });

                    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        // Use click() so WooCommerce's radio click handler and
                        // the native change event both fire, like a real click.
                        items[(idx + 1) % items.length].click();
                        items[(idx + 1) % items.length].focus();
                    }

                    if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                        e.preventDefault();
                        items[(idx - 1 + items.length) % items.length].click();
                        items[(idx - 1 + items.length) % items.length].focus();
                    }
                });
            }
        }

        initPaymentOptions();

        // ── Sidebar Place Order Button ──
        const sidebarOrderBtn = document.getElementById('place_order_sidebar');

        if (sidebarOrderBtn) {
            sidebarOrderBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const mainOrderBtn = document.getElementById('place_order');
                const form = mainOrderBtn ? mainOrderBtn.closest('form') : null;
                if (form) {
                    // Ensure the checked radio is still selected
                    const checkedRadio = form.querySelector('input[name="payment_method"]:checked');
                    if (checkedRadio) {
                        checkedRadio.checked = true;
                    }
                    // requestSubmit() dispatches a real submit event.
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit(mainOrderBtn || undefined);
                    } else {
                        form.submit();
                    }
                }
            });
        }

        // ── Notification System ──
        function showNotification(message, type) {
            type = type || 'info';

            const existingNotification = document.querySelector('.checkout-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            const notification = document.createElement('div');
            notification.className = 'checkout-notification';

            const notificationContent = document.createElement('div');
            notificationContent.className = 'notification-content notification-' + type;

            // Build via DOM nodes: message can contain user input (e.g. coupon
            // codes) and must never be interpolated into innerHTML.
            const messageSpan = document.createElement('span');
            messageSpan.className = 'notification-message';
            messageSpan.textContent = message;

            const closeBtn = document.createElement('button');
            closeBtn.className = 'notification-close';
            closeBtn.setAttribute('aria-label', 'Close notification');
            closeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="16" height="16"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            closeBtn.addEventListener('click', function() {
                hideNotification(notification);
            });

            notificationContent.appendChild(messageSpan);
            notificationContent.appendChild(closeBtn);
            notification.appendChild(notificationContent);

            document.body.appendChild(notification);

            setTimeout(function() {
                notification.classList.add('show');
            }, 10);

            setTimeout(function() {
                hideNotification(notification);
            }, 5000);
        }

        function hideNotification(notification) {
            notification.classList.remove('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }

        // ── Loading State for Form Submission ──
        function initPlaceOrderLoadingState() {
            const orderBtn = document.getElementById('place_order');
            if (!orderBtn || orderBtn.dataset.shopnexLoadingBound === '1') {
                return;
            }
            orderBtn.dataset.shopnexLoadingBound = '1';

            orderBtn.addEventListener('click', function() {
                const form = this.closest('form');
                if (!form || !form.checkValidity()) {
                    return;
                }

                const mainBtn = this;
                const sidebarBtn = document.getElementById('place_order_sidebar');

                mainBtn.classList.add('loading');
                mainBtn.innerHTML = '<span class="btn-spinner"></span>Processing...';

                if (sidebarBtn) {
                    sidebarBtn.disabled = true;
                    sidebarBtn.innerHTML = '<span class="btn-spinner"></span>Processing...';
                }

                // Never disable the submitter synchronously
                setTimeout(function() {
                    mainBtn.disabled = true;
                }, 0);
            });
        }

        initPlaceOrderLoadingState();

        // ── Keyboard Navigation Enhancement ──
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const notification = document.querySelector('.checkout-notification');
                if (notification) {
                    hideNotification(notification);
                }
            }
        });

        // ── Real-time Order Total Update ──
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('updated_checkout', function() {
                // Re-initialize payment method handlers
                initPaymentOptions();

                // Re-bind the place order loading state: WooCommerce replaces the
                // #payment fragment, so previously bound handlers are lost.
                initPlaceOrderLoadingState();

                // Re-initialize shipping Select2 if shipping fields are visible
                if (shippingAddressFields && shippingAddressFields.classList.contains('show')) {
                    setTimeout(function() {
                        initShippingSelects();
                    }, 100);
                }
            });
        }

    });

})();
