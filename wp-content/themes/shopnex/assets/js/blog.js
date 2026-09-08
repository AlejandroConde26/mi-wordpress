/**
 *
 * @package shopnex
 */

(function ($) {
    'use strict';

    /**
     * Toggle tag pill active state
     */
    window.toggleTag = function (el) {
        el.classList.toggle('active');
    };

    /**
     * Blog Card Fade-In Animation
     */
    function initBlogCardAnimations() {
        var cards = document.querySelectorAll('.blog-card');
        if (!cards.length) return;

        cards.forEach(function (card, index) {
            card.style.animationDelay = (index * 0.08) + 's';
        });
    }

    /**
     * Fade-in Animation
     */
    function initFadeInAnimations() {
        var fadeEls = document.querySelectorAll('.fade-in');
        if (!fadeEls.length) return;

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            fadeEls.forEach(function (el) {
                observer.observe(el);
            });
        } else {
            // Fallback: show all immediately
            fadeEls.forEach(function (el) {
                el.classList.add('visible');
            });
        }
    }

    /**
     * Share Functions
     */
    window.shopnex_share_twitter = function () {
        var url = encodeURIComponent(window.location.href);
        var text = encodeURIComponent(document.title);
        window.open('https://x.com/intent/tweet?url=' + url + '&text=' + text, '_blank', 'width=600,height=400');
    };

    window.shopnex_share_linkedin = function () {
        var url = encodeURIComponent(window.location.href);
        window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, '_blank', 'width=600,height=400');
    };

    window.shopnex_share_facebook = function () {
        var url = encodeURIComponent(window.location.href);
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '_blank', 'width=600,height=400');
    };

    window.shopnex_share_instagram = function () {
        var url = window.location.href;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function () {
                alert('Link copied! Share it on Instagram.');
            });
        } else {
            alert('Copy this link to share on Instagram: ' + url);
        }
    };

    window.shopnex_share_pinterest = function () {
        var url = encodeURIComponent(window.location.href);
        var description = encodeURIComponent(document.title);
        var media = '';
        var heroImg = document.querySelector('.article-hero img');
        if (heroImg) {
            media = encodeURIComponent(heroImg.src);
        }
        window.open('https://pinterest.com/pin/create/button/?url=' + url + '&description=' + description + '&media=' + media, '_blank', 'width=600,height=400');
    };

    window.shopnex_share_email = function () {
        var url = window.location.href;
        var subject = encodeURIComponent(document.title);
        var body = encodeURIComponent('Check out this article: ' + url);
        window.location.href = 'mailto:?subject=' + subject + '&body=' + body;
    };

    window.shopnex_copy_link = function (btn) {
        var url = window.location.href;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function () {
                showCopyFeedback(btn);
            });
        } else {
            var input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showCopyFeedback(btn);
        }
    };

    function showCopyFeedback(btn) {
        var original = btn.innerHTML;
        btn.innerHTML = '<svg viewBox="0 0 24 24" width="14" height="14"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg> Copied!';
        btn.style.color = 'var(--brand-accent)';
        btn.style.borderColor = 'var(--brand-accent)';
        setTimeout(function () {
            btn.innerHTML = original;
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    }

    
    function getPostIdFromURL() {
        var body = document.querySelector('body');
        var classes = body.className;
        var match = classes.match(/postid-(\d+)/);
        return match ? parseInt(match[1]) : null;
    }


    /**
     * Delegated event listeners for data-attribute triggers
     */
    function initDelegatedEvents() {
        // Share buttons (data-share="instagram|pinterest|email|facebook|twitter|linkedin")
        $(document).on('click', '[data-share]', function (e) {
            e.preventDefault();
            var platform = $(this).data('share');
            if (typeof window['shopnex_share_' + platform] === 'function') {
                window['shopnex_share_' + platform]();
            }
        });

        // Tag toggle (data-toggle-tag)
        $(document).on('click', '[data-toggle-tag]', function () {
            window.toggleTag(this);
        });

        // Comment like (data-like-comment)
        $(document).on('click', '[data-like-comment]', function () {
            window.shopnex_like_comment(this);
        });
    }

    /**
     * Initialize on DOM ready
     */
    $(document).ready(function () {
        initBlogCardAnimations();
        initFadeInAnimations();
        initDelegatedEvents();
    });

})(jQuery);
