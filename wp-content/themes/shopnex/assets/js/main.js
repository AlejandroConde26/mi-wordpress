/**
 * Main JavaScript
 */

(function($) {
    'use strict';

    // New Arrivals Scroller
    window.scrollNewArrivals = function(direction) {
        const scroller = document.getElementById('newArrivalsScroller');
        if (scroller) {
            scroller.scrollBy({
                left: direction * 300,
                behavior: 'smooth'
            });
        }
    };

    // Mobile Menu (fullscreen overlay)
    var mobileMenuFocusTrapHandler = null;

    window.toggleMobileMenu = function() {
        const menu = document.getElementById('mobileMenuDrawer');
        const overlay = document.getElementById('mobileMenuOverlay');
        const hamburger = document.getElementById('hamburger');
        
        if (menu && overlay) {
            const isOpen = menu.classList.contains('open');
            if (isOpen) {
                menu.classList.remove('open');
                overlay.classList.remove('open');
                if (hamburger) hamburger.classList.remove('active');
                document.body.style.overflow = '';
                // Remove focus trap
                if (mobileMenuFocusTrapHandler) {
                    document.removeEventListener('keydown', mobileMenuFocusTrapHandler);
                    mobileMenuFocusTrapHandler = null;
                }
                // Return focus to hamburger button
                if (hamburger) {
                    setTimeout(function() { hamburger.focus(); }, 420);
                }
            } else {
                menu.classList.add('open');
                overlay.classList.add('open');
                if (hamburger) hamburger.classList.add('active');
                document.body.style.overflow = 'hidden';
                // Add focus trap — fully control Tab navigation inside the menu
                mobileMenuFocusTrapHandler = function(e) {
                    if (e.key !== 'Tab') return;
                    e.preventDefault();

                    var focusableSelectors = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';
                    var allFocusables = Array.prototype.slice.call(menu.querySelectorAll(focusableSelectors)).filter(function(el) {
                        return el.offsetParent !== null;
                    });
                    if (allFocusables.length === 0) return;

                    // Reorder: close button should be last in tab cycle
                    var closeBtn = document.getElementById('mobileMenuClose');
                    var reordered = allFocusables.filter(function(el) { return el !== closeBtn; });
                    if (closeBtn && allFocusables.indexOf(closeBtn) !== -1) {
                        reordered.push(closeBtn);
                    }

                    var currentIndex = reordered.indexOf(document.activeElement);

                    if (e.shiftKey) {
                        // Shift+Tab: go backward, wrap from first to last
                        if (currentIndex <= 0) {
                            reordered[reordered.length - 1].focus();
                        } else {
                            reordered[currentIndex - 1].focus();
                        }
                    } else {
                        // Tab: go forward, wrap from last to first
                        if (currentIndex === -1 || currentIndex >= reordered.length - 1) {
                            reordered[0].focus();
                        } else {
                            reordered[currentIndex + 1].focus();
                        }
                    }
                };
                document.addEventListener('keydown', mobileMenuFocusTrapHandler);
                // Auto-focus first menu link
                setTimeout(function() {
                    var firstLink = menu.querySelector('.mobile-menu-links > li > a');
                    if (firstLink) firstLink.focus();
                }, 200);
            }
        }
    };

    // Cart Drawer
    window.toggleCart = function() {
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartOverlay');
        
        if (drawer && overlay) {
            drawer.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
        }
    };

    // Search Overlay
    var searchFocusTrapHandler = null;

    window.toggleSearch = function() {
        var overlay = document.getElementById('searchOverlay');
        var input = overlay ? overlay.querySelector('.search-field') : null;
        
        if (overlay) {
            var isActive = overlay.classList.contains('active');
            if (isActive) {
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                // Remove focus trap
                if (searchFocusTrapHandler) {
                    document.removeEventListener('keydown', searchFocusTrapHandler);
                    searchFocusTrapHandler = null;
                }
            } else {
                overlay.classList.add('active');
                if (input) {
                    setTimeout(function() { input.focus(); }, 200);
                }
                document.body.style.overflow = 'hidden';
                // Add focus trap
                searchFocusTrapHandler = function(e) {
                    if (e.key !== 'Tab') return;
                    var focusableSelectors = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';
                    var focusables = Array.prototype.slice.call(overlay.querySelectorAll(focusableSelectors)).filter(function(el) {
                        return el.offsetParent !== null;
                    });
                    if (focusables.length === 0) return;

                    var firstEl = focusables[0];
                    var lastEl = focusables[focusables.length - 1];

                    if (e.shiftKey) {
                        if (document.activeElement === firstEl) {
                            e.preventDefault();
                            lastEl.focus();
                        }
                    } else {
                        if (document.activeElement === lastEl) {
                            e.preventDefault();
                            firstEl.focus();
                        }
                    }
                };
                document.addEventListener('keydown', searchFocusTrapHandler);
            }
        }
    };

    // ESC key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const searchOverlay = document.getElementById('searchOverlay');
            const cartDrawer = document.getElementById('cartDrawer');
            const mobileMenu = document.getElementById('mobileMenuDrawer');
            
            if (searchOverlay && searchOverlay.classList.contains('active')) {
                toggleSearch();
            }
            if (cartDrawer && cartDrawer.classList.contains('open')) {
                toggleCart();
            }
            if (mobileMenu && mobileMenu.classList.contains('open')) {
                toggleMobileMenu();
            }
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Initialize on DOM ready
    $(document).ready(function() {
        initHeaderActions();
        initStickyHeader();
        initDropdownArrows();
    });

    function initDropdownArrows() {
        var arrows = document.querySelectorAll('.nav-links .menu-item-has-children > a');
        arrows.forEach(function(link) {
            if (!link.querySelector('.dropdown-arrow')) {
                var svg = document.createElement('span');
                svg.className = 'dropdown-arrow';
                svg.innerHTML = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';
                link.appendChild(svg);
            }
        });
    }

    function initMobileSubmenuToggles() {
        var menuItems = document.querySelectorAll('.mobile-menu-links .menu-item-has-children');
        menuItems.forEach(function(item) {
            if (item.querySelector(':scope > .mobile-submenu-toggle')) return;

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'mobile-submenu-toggle';
            btn.setAttribute('aria-label', 'Toggle submenu');
            btn.setAttribute('aria-expanded', 'false');
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';

            var link = item.querySelector(':scope > a');
            if (link && link.nextSibling) {
                item.insertBefore(btn, link.nextSibling);
            } else {
                item.appendChild(btn);
            }

            function setOpen(open) {
                if (open) {
                    item.classList.add('submenu-open');
                    btn.setAttribute('aria-expanded', 'true');
                } else {
                    item.classList.remove('submenu-open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                setOpen(!item.classList.contains('submenu-open'));
            });

            // Sync open state only when a child inside the submenu receives focus
            var subMenu = item.querySelector(':scope > .sub-menu');
            if (subMenu) {
                subMenu.addEventListener('focusin', function() {
                    setOpen(true);
                });
            }
        });
    }

    // Sticky Header Scroll Effect
    function initStickyHeader() {
        const header = document.getElementById('mainHeader');
        if (!header) return;

        function updateHeader() {
            if (window.scrollY > 40) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', updateHeader, { passive: true });
        updateHeader();
    }

    // Header Action Buttons
    function initHeaderActions() {
        // Search toggle
        const searchToggle = document.getElementById('search-toggle');
        if (searchToggle) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSearch();
            });
        }

        // Search close button
        const searchClose = document.getElementById('search-close');
        if (searchClose) {
            searchClose.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSearch();
            });
        }

        // Search overlay click to close (via backdrop)
        const searchOverlay = document.getElementById('searchOverlay');
        if (searchOverlay) {
            const backdrop = searchOverlay.querySelector('.search-overlay-backdrop');
            if (backdrop) {
                backdrop.addEventListener('click', function(e) {
                    toggleSearch();
                });
            }
        }

        // Cart drawer close button
        const cartCloseBtn = document.querySelector('.drawer-close[data-drawer="cart"]');
        if (cartCloseBtn) {
            cartCloseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleCart();
            });
        }

        // Cart overlay click to close
        const cartOverlay = document.getElementById('cartOverlay');
        if (cartOverlay) {
            cartOverlay.addEventListener('click', function(e) {
                toggleCart();
            });
        }

        // Mobile menu toggle (hamburger)
        const hamburger = document.getElementById('hamburger');
        if (hamburger) {
            hamburger.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }

        // Legacy mobile menu toggle support
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }

        // Mobile menu drawer close button (legacy)
        const mobileCloseBtn = document.querySelector('.drawer-close[data-drawer="mobile"]');
        if (mobileCloseBtn) {
            mobileCloseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }

        // Mobile menu close button inside drawer
        const mobileMenuCloseBtn = document.getElementById('mobileMenuClose');
        if (mobileMenuCloseBtn) {
            mobileMenuCloseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }

        // Mobile menu overlay click to close
        const mobileOverlay = document.getElementById('mobileMenuOverlay');
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function(e) {
                toggleMobileMenu();
            });
        }

        // Close mobile menu when clicking links inside it (leaf items only)
        var mobileLinks = document.querySelectorAll('.mobile-menu-links a');
        mobileLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                var parentLi = this.closest('.menu-item-has-children');
                if (parentLi && parentLi.querySelector(':scope > .sub-menu')) {
                    return;
                }
                toggleMobileMenu();
            });
        });

        initMobileSubmenuToggles();

        // Search tags click to populate search field
        document.querySelectorAll('.search-tag').forEach(function(tag) {
            tag.addEventListener('click', function(e) {
                e.preventDefault();
                var searchField = searchOverlay ? searchOverlay.querySelector('.search-field') : null;
                if (searchField) {
                    searchField.value = this.textContent.trim();
                    var form = searchField.closest('form');
                    if (form) {
                        form.submit();
                    }
                }
            });
        });

        // Toast close button delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-toast-close]')) {
                e.target.closest('.toast').remove();
            }
        });
    }

})(jQuery);
