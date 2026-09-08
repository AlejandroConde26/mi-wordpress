/**
 * Product Single Page JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {

    // ── Image Gallery ──
    var mainImg = document.getElementById('mainImg');
    var thumbBtns = document.querySelectorAll('.shopnex-thumb-btn');
    var images = [];
    var currentImageIndex = 0;

    // Fallback for single-image products (no thumbnails)
    if (thumbBtns.length === 0 && mainImg && mainImg.getAttribute('src')) {
        images.push(mainImg.getAttribute('src'));
    }

    thumbBtns.forEach(function(btn, index) {
        var fullUrl = btn.getAttribute('data-full');
        if (fullUrl) {
            images.push(fullUrl);
        }
        btn.addEventListener('click', function() {
            shopnexChangeImage(index, btn);
        });
    });

    // Global function for changing gallery image
    window.shopnexChangeImage = function(index, btn) {
        currentImageIndex = index;
        if (mainImg && images[index]) {
            mainImg.src = images[index];
        }
        thumbBtns.forEach(function(b) {
            b.classList.remove('active');
        });
        if (btn) {
            btn.classList.add('active');
        } else if (thumbBtns[index]) {
            thumbBtns[index].classList.add('active');
        }
    };

    // ── Lightbox ──
    var lightbox = document.getElementById('lightbox');
    var lightboxImg = document.getElementById('lightboxImg');
    var lightboxClose = document.querySelector('.shopnex-lightbox-close');

    function openLightbox() {
        if (lightbox && lightboxImg && images[currentImageIndex]) {
            lightboxImg.src = images[currentImageIndex];
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLightbox() {
        if (lightbox) {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (mainImg) {
        mainImg.addEventListener('click', openLightbox);
    }

    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (lightbox && lightbox.classList.contains('active')) {
                closeLightbox();
            }
            var reviewModal = document.getElementById('reviewModal');
            if (reviewModal && reviewModal.classList.contains('active')) {
                closeReviewModal();
            }
        }
    });


    // ── Size Selector (Simple Products) ──
    var sizeBtns = document.querySelectorAll('.shopnex-size-btn:not(.variations_form .shopnex-size-btn)');

    sizeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (this.classList.contains('oos')) return;
            sizeBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
        });
    });


    // ── Variable Product Attribute Selector ──
    var variationsForm = document.querySelector('.variations_form');

    if (variationsForm) {
        var variationsScript = document.getElementById('shopnex-variations-data');
        var variationsData = [];
        try {
            variationsData = variationsScript ? JSON.parse(variationsScript.textContent) : [];
        } catch (e) {
            // Silently handle parse errors
        }

        var selectedAttributes = {};
        var productPriceBlock = document.getElementById('productPriceBlock');
        var originalPriceHTML = productPriceBlock ? productPriceBlock.innerHTML : '';

        variationsForm.querySelectorAll('.shopnex-size-btn, .shopnex-color-swatch').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (this.classList.contains('oos')) return;

                var attribute = this.getAttribute('data-attribute');
                var value = this.getAttribute('data-value');
                var displayName = this.getAttribute('data-display-name') || value;

                if (!attribute || !value) return;

                attribute = attribute.toLowerCase();

                // Deactivate siblings
                var origAttr = this.getAttribute('data-attribute');
                variationsForm.querySelectorAll('.shopnex-size-btn[data-attribute="' + origAttr + '"], .shopnex-color-swatch[data-attribute="' + origAttr + '"]').forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');
                selectedAttributes[attribute] = value;
                findMatchingVariation();
            });
        });

        function findMatchingVariation() {
            var variationIdInput = document.getElementById('variation_id');
            if (!variationIdInput || !variationsData.length) {
                return;
            }

            var requiredAttributes = Object.keys(variationsData[0] ? variationsData[0].attributes || {} : {});

            var allSelected = requiredAttributes.every(function(attr) { return selectedAttributes[attr]; });

            if (!allSelected) {
                variationIdInput.value = '';
                resetVariationPrice();
                return;
            }

            var matchedVariation = variationsData.find(function(variation) {
                return requiredAttributes.every(function(attr) {
                    var variationValue = variation.attributes[attr];
                    if (variationValue === '') return true;
                    return variationValue === selectedAttributes[attr];
                });
            });

            if (matchedVariation) {
                variationIdInput.value = matchedVariation.variation_id;
                updateVariationPrice(matchedVariation);

                if (matchedVariation.image && matchedVariation.image.src && mainImg) {
                    mainImg.src = matchedVariation.image.src;
                }
            } else {
                variationIdInput.value = '';
                resetVariationPrice();
            }
        }

        function resetVariationPrice() {
            if (productPriceBlock && originalPriceHTML) {
                productPriceBlock.innerHTML = originalPriceHTML;
            }
            var priceDisplay = document.getElementById('variationPriceDisplay');
            if (priceDisplay) {
                priceDisplay.style.display = 'none';
            }
        }

        function updateVariationPrice(variation) {
            if (!productPriceBlock) return;

            var html = '';

            if (variation.display_price !== undefined) {
                if (variation.display_regular_price && variation.display_price < variation.display_regular_price) {
                    var savings = variation.display_regular_price - variation.display_price;
                    var percentage = Math.round((savings / variation.display_regular_price) * 100);
                    html += '<span class="shopnex-price-current">' + variation.price_html.match(/<ins[^>]*>([\s\S]*?)<\/ins>/ ? variation.price_html.match(/<ins[^>]*>([\s\S]*?)<\/ins>/)[1] : wc_price(variation.display_price)) + '</span>';
                    html += '<span class="shopnex-price-original">' + wc_price(variation.display_regular_price) + '</span>';
                } else {
                    html += '<span class="shopnex-price-current">' + (variation.price_html || wc_price(variation.display_price)) + '</span>';
                }
            }

            if (html) {
                productPriceBlock.innerHTML = html;
            }
        }

        function wc_price(price) {
            var symbol = '$';
            var currencyPos = 'left';
            var decimalSep = '.';
            var thousandSep = ',';
            var decimals = 2;

            if (typeof shopnex_product_single !== 'undefined') {
                symbol = shopnex_product_single.currency_symbol || '$';
                currencyPos = shopnex_product_single.currency_pos || 'left';
                decimalSep = shopnex_product_single.decimal_sep || '.';
                thousandSep = shopnex_product_single.thousand_sep || ',';
                decimals = parseInt(shopnex_product_single.decimals, 10) || 2;
            }

            var formattedPrice = parseFloat(price).toFixed(decimals);
            var parts = formattedPrice.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSep);
            formattedPrice = parts.join(decimalSep);

            var priceHtml = '';
            switch (currencyPos) {
                case 'left':
                    priceHtml = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>' + formattedPrice;
                    break;
                case 'right':
                    priceHtml = formattedPrice + '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';
                    break;
                case 'left_space':
                    priceHtml = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>&nbsp;' + formattedPrice;
                    break;
                case 'right_space':
                    priceHtml = formattedPrice + '&nbsp;<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';
                    break;
                default:
                    priceHtml = '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>' + formattedPrice;
            }

            return '<span class="woocommerce-Price-amount amount"><bdi>' + priceHtml + '</bdi></span>';
        }
    }


    // ── Quantity Selector ──
    var qtyValue = document.getElementById('qtyValue');
    var qtyBtns = document.querySelectorAll('.shopnex-qty-btn');

    if (qtyValue && qtyBtns.length > 0) {
        qtyBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var change = parseInt(this.getAttribute('data-qty-change'));
                var val = parseInt(qtyValue.value) + change;
                if (val < 1) val = 1;
                if (val > 99) val = 99;
                qtyValue.value = val;
            });
        });
    }


    // ── Add to Cart Button ──
    var addToCartBtn = document.querySelector('.shopnex-add-to-cart');

    // Prevent the hidden WooCommerce form from submitting via normal POST.
    var wooForm = document.querySelector('form.cart');
    if (wooForm) {
        wooForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }

    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var form = document.querySelector('form.cart');
            if (!form) {
                return;
            }

            var quantity = qtyValue ? parseInt(qtyValue.value) : 1;
            var productId = form.querySelector('input[name="product_id"]') ? form.querySelector('input[name="product_id"]').value : '';
            var variationId = document.getElementById('variation_id') ? document.getElementById('variation_id').value : '';

            if (!productId) return;

            var isVariable = document.querySelector('.variations_form');
            if (isVariable && !variationId) {
                showToast(shopnex_product_single && shopnex_product_single.texts ? shopnex_product_single.texts.select_options : 'Please select all options');
                return;
            }

            var originalHTML = this.innerHTML;
            this.style.pointerEvents = 'none';
            this.style.opacity = '0.7';

            var formData = new FormData();
            if (variationId) {
                formData.append('product_id', variationId);
            } else {
                formData.append('product_id', productId);
            }
            formData.append('quantity', quantity);

            var addToCartUrl = '';
            if (typeof shopnex_wc_params !== 'undefined' && shopnex_wc_params.wc_ajax_url) {
                addToCartUrl = shopnex_wc_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart');
            } else {
                addToCartUrl = '/?wc-ajax=add_to_cart';
            }

            fetch(addToCartUrl, {
                method: 'POST',
                body: formData,
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (!data.error) {

                    // Update button state
                    addToCartBtn.style.background = '#16a34a';
                    addToCartBtn.style.opacity = '';
                    addToCartBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span>Added!</span>';

                    // Apply cart fragments
                    if (data.fragments && typeof jQuery !== 'undefined') {
                        var fragmentSelectors = Object.keys(data.fragments);
                        for (var i = 0; i < fragmentSelectors.length; i++) {
                            var selector = fragmentSelectors[i];
                            jQuery(selector).replaceWith(data.fragments[selector]);
                        }
                    }

                    // Trigger WooCommerce added_to_cart event
                    if (typeof jQuery !== 'undefined') {
                        jQuery(document.body).trigger('added_to_cart', [data.fragments, data.cart_hash, addToCartBtn]);
                    }

                    setTimeout(function() {
                        addToCartBtn.style.background = '';
                        addToCartBtn.style.pointerEvents = '';
                        addToCartBtn.innerHTML = originalHTML;
                    }, 2000);
                } else {
                    addToCartBtn.style.pointerEvents = '';
                    addToCartBtn.style.opacity = '';
                }
            })
            .catch(function(error) {
                addToCartBtn.style.pointerEvents = '';
                addToCartBtn.style.opacity = '';
            });
        });
    }


    // ── Toast Notification ──
    function showToast(message) {
        // Extract message string from possible object
        if (typeof message === 'object' && message !== null) {
            message = message.message || 'An error occurred.';
        }
        if (!message) {
            message = 'An error occurred.';
        }

        var existing = document.querySelector('.shopnex-toast');
        if (existing) {
            existing.remove();
        }

        var toast = document.createElement('div');
        toast.className = 'shopnex-toast';
        toast.textContent = message;
        toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1C1C1C;color:#fff;padding:14px 24px;border-radius:8px;font-size:14px;z-index:100000;box-shadow:0 4px 12px rgba(0,0,0,0.15);opacity:0;transition:opacity 0.3s ease;';
        document.body.appendChild(toast);

        requestAnimationFrame(function() {
            toast.style.opacity = '1';
        });

        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }


    // ── Review Modal ──
    var writeReviewBtn = document.getElementById('writeReviewBtn');
    var reviewModal = document.getElementById('reviewModal');
    var reviewModalOverlay = document.getElementById('reviewModalOverlay');
    var reviewModalClose = document.getElementById('reviewModalClose');
    var starRatingInput = document.getElementById('starRatingInput');
    var ratingInput = document.getElementById('ratingInput');
    var reviewForm = document.getElementById('reviewForm');

    function openReviewModal() {
        if (reviewModal) {
            reviewModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeReviewModal() {
        if (reviewModal) {
            reviewModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (writeReviewBtn) {
        writeReviewBtn.addEventListener('click', openReviewModal);
    }

    if (reviewModalOverlay) {
        reviewModalOverlay.addEventListener('click', closeReviewModal);
    }

    if (reviewModalClose) {
        reviewModalClose.addEventListener('click', closeReviewModal);
    }

    // Star rating input
    if (starRatingInput) {
        var stars = starRatingInput.querySelectorAll('.star');

        function setActiveStars(fromIndex) {
            stars.forEach(function(s, i) {
                s.classList.toggle('active', i >= fromIndex);
            });
        }

        stars.forEach(function(star, index) {
            star.addEventListener('click', function() {
                var rating = 5 - index;
                ratingInput.value = rating;
                setActiveStars(index);
            });

            star.addEventListener('mouseenter', function() {
                setActiveStars(index);
            });
        });

        starRatingInput.addEventListener('mouseleave', function() {
            var currentRating = parseInt(ratingInput.value) || 0;
            if (currentRating > 0) {
                setActiveStars(5 - currentRating);
            } else {
                stars.forEach(function(s) { s.classList.remove('active'); });
            }
        });
    }

    // Handle form submission via AJAX
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            if (!ratingInput.value) {
                showToast('Please select a rating');
                return;
            }

            // Get submit button and set loading state
            var submitBtn = reviewForm.querySelector('.shopnex-submit-review');
            var originalBtnHTML = submitBtn ? submitBtn.innerHTML : '';
            var originalBtnWidth = submitBtn ? submitBtn.offsetWidth : 0;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.width = originalBtnWidth + 'px';
                submitBtn.style.pointerEvents = 'none';
                submitBtn.style.opacity = '0.7';
                submitBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:shopnex-spin 0.8s linear infinite;vertical-align:middle;margin-right:8px;"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg><span>Submitting...</span>';
            }

            var ajaxUrl = '';
            if (typeof shopnex_product_single !== 'undefined' && shopnex_product_single.ajax_url) {
                ajaxUrl = shopnex_product_single.ajax_url;
            } else {
                ajaxUrl = '/wp-admin/admin-post.php';
            }

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    var successMsg = 'Thank you for your review!';
                    var pendingMsg = 'Your review is pending approval.';
                    if (typeof shopnex_product_single !== 'undefined' && shopnex_product_single.texts) {
                        successMsg = shopnex_product_single.texts.review_submitted || successMsg;
                        pendingMsg = shopnex_product_single.texts.review_pending || pendingMsg;
                    }

                    reviewForm.innerHTML = '<div style="text-align:center;padding:40px 20px;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent, #B8977E);display:block;margin:0 auto 16px;"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg><h4 style="font-size:18px;margin-bottom:8px;color:var(--brand-dark, #1C1C1C);">' + successMsg + '</h4><p style="color:var(--brand-muted, #9C9792);font-size:14px;">' + pendingMsg + '</p></div>';

                    setTimeout(function() {
                        closeReviewModal();
                    }, 3000);
                } else {
                    showToast(data.data || 'Failed to submit review.');
                    resetSubmitBtn();
                }
            })
            .catch(function(error) {
                showToast('Failed to submit review.');
                resetSubmitBtn();
            });

            function resetSubmitBtn() {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.width = '';
                    submitBtn.style.pointerEvents = '';
                    submitBtn.style.opacity = '';
                    submitBtn.innerHTML = originalBtnHTML;
                }
            }
        });
    }


    // ── Load More Reviews ──
    var loadMoreBtn = document.getElementById('loadMoreReviews');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            var hiddenReviews = document.querySelectorAll('.shopnex-review-hidden');
            var perPage = parseInt(this.getAttribute('data-per-page')) || 3;
            var shown = 0;

            hiddenReviews.forEach(function(review) {
                if (shown < perPage) {
                    review.style.display = '';
                    review.classList.remove('shopnex-review-hidden');
                    review.style.opacity = '0';
                    review.style.transform = 'translateY(10px)';
                    requestAnimationFrame(function() {
                        review.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        review.style.opacity = '1';
                        review.style.transform = 'translateY(0)';
                    });
                    shown++;
                }
            });

            var remaining = document.querySelectorAll('.shopnex-review-hidden');
            if (remaining.length === 0) {
                loadMoreBtn.style.display = 'none';
            }
        });
    }


    // ── Scroll Top Button ──
    var scrollTopBtn = document.getElementById('scrollTop');

    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });

        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ── Product Tabs ──
    var tabHeaders = document.querySelectorAll('.shopnex-tab-header');
    var tabPanels = document.querySelectorAll('.shopnex-tab-panel');
    if (tabHeaders.length) {
        tabHeaders.forEach(function(header) {
            header.addEventListener('click', function() {
                var tab = header.getAttribute('data-tab');
                tabHeaders.forEach(function(h) { h.classList.remove('active-tab'); });
                tabPanels.forEach(function(p) { p.classList.remove('active-panel'); });
                header.classList.add('active-tab');
                var panel = document.getElementById('panel-' + tab);
                if (panel) {
                    panel.classList.add('active-panel');
                }
            });
        });
    }

    // ── Fade In Animation ──
    var fadeElements = document.querySelectorAll('.shopnex-pdp-main, .shopnex-related-section, .shopnex-reviews-section');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        fadeElements.forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
            observer.observe(el);
        });
    }

});
