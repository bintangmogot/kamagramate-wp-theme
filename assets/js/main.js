document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuOpen = document.getElementById('mobile-menu-open');
    const menuClose = document.getElementById('mobile-menu-close');
    const menuDrawer = document.getElementById('mobile-menu-drawer');
    const menuOverlay = document.getElementById('mobile-menu-overlay');

    function openMenu() {
        if (!menuDrawer || !menuOverlay) return;
        
        // Show overlay
        menuOverlay.classList.remove('hidden');
        menuOverlay.classList.add('block');
        
        // Slide in drawer
        menuDrawer.classList.remove('-translate-x-full');
        menuDrawer.classList.add('translate-x-0');
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (!menuDrawer || !menuOverlay) return;
        
        // Hide overlay
        menuOverlay.classList.add('hidden');
        menuOverlay.classList.remove('block');
        
        // Slide out drawer
        menuDrawer.classList.add('-translate-x-full');
        menuDrawer.classList.remove('translate-x-0');
        
        // Restore body scroll
        document.body.style.overflow = '';
    }

    if (menuOpen) menuOpen.addEventListener('click', openMenu);
    if (menuClose) menuClose.addEventListener('click', closeMenu);
    if (menuOverlay) menuOverlay.addEventListener('click', closeMenu);

    // Mobile Navigation Submenu Accordion
    document.querySelectorAll('.mobile-nav .menu-item-has-children').forEach((item) => {
        const toggleBtn = item.querySelector('.mobile-submenu-toggle');
        const subMenu = item.querySelector('.sub-menu');
        const link = item.querySelector('a');

        if (!subMenu) return;

        function toggleSubmenu(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            const isOpen = subMenu.classList.contains('is-open');
            if (isOpen) {
                subMenu.classList.remove('is-open');
                if (toggleBtn) {
                    toggleBtn.classList.remove('is-active');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            } else {
                subMenu.classList.add('is-open');
                if (toggleBtn) {
                    toggleBtn.classList.add('is-active');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                }
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSubmenu);
        }

        // If parent link href is "#" or empty, clicking the link also toggles the submenu
        if (link) {
            const href = link.getAttribute('href');
            if (!href || href === '#' || href === 'javascript:void(0)') {
                link.addEventListener('click', toggleSubmenu);
            }
        }
    });

    document.querySelectorAll('[data-product-description-toggle]').forEach((button) => {
        const description = document.getElementById(button.getAttribute('aria-controls'));
        if (!description) return;

        description.classList.add('line-clamp-2');
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            description.classList.toggle('line-clamp-2', expanded);
            button.setAttribute('aria-expanded', String(!expanded));
            button.textContent = expanded ? 'Read more' : 'Read less';
        });
    });

    // Automatic Table Enhancer for WYSIWYG Content
    document.querySelectorAll('.prose table').forEach((table) => {
        // 1. Promote first row to <thead> if missing
        if (!table.querySelector('thead')) {
            const firstRow = table.querySelector('tbody tr:first-child, tr:first-child');
            if (firstRow) {
                const thead = document.createElement('thead');
                thead.appendChild(firstRow);
                table.insertBefore(thead, table.firstChild);
            }
        }

        // 2. Wrap standalone tables in a responsive scroll container
        if (!table.parentElement.classList.contains('table-wrapper') && !table.closest('figure.wp-block-table')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-wrapper';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }

        // 3. Highlight featured product column in comparison tables
        const headerCells = table.querySelectorAll('thead th, thead td');
        if (headerCells.length >= 2) {
            let highlightIdx = -1;
            const pageHeading = (document.querySelector('h1')?.textContent || '').trim().toLowerCase();

            // Check if any column header matches the current product title
            if (pageHeading) {
                headerCells.forEach((th, idx) => {
                    const txt = th.textContent.trim().toLowerCase();
                    if (idx > 0 && txt && (pageHeading.includes(txt) || txt.includes(pageHeading.split(' ')[0]))) {
                        highlightIdx = idx;
                    }
                });
            }

            // Fallback: check if any header contains 'waklert' or 'recommended'
            if (highlightIdx === -1) {
                headerCells.forEach((th, idx) => {
                    const txt = th.textContent.trim().toLowerCase();
                    if (idx > 0 && (txt.includes('waklert') || txt.includes('recommended'))) {
                        highlightIdx = idx;
                    }
                });
            }

            // Fallback: if comparison table (>= 3 columns) and no column matched, highlight the last column
            if (highlightIdx === -1 && headerCells.length >= 3) {
                highlightIdx = headerCells.length - 1;
            }

            if (highlightIdx !== -1) {
                const targetTh = headerCells[highlightIdx];
                targetTh.classList.add('waklert-highlight-header');
                table.querySelectorAll(`tbody tr td:nth-child(${highlightIdx + 1}), tbody tr th:nth-child(${highlightIdx + 1})`).forEach((td) => {
                    td.classList.add('waklert-highlight-cell');
                });
            }
        }
    });

    // Support Email Click Helper: Copies email address as a reliable backup when mailto link is clicked
    document.querySelectorAll('a[data-support-email]').forEach((link) => {
        link.addEventListener('click', () => {
            const email = link.getAttribute('data-support-email');
            if (!email) return;

            const copyPromise = (navigator.clipboard && navigator.clipboard.writeText)
                ? navigator.clipboard.writeText(email)
                : new Promise((resolve, reject) => {
                    try {
                        const ta = document.createElement('textarea');
                        ta.value = email;
                        ta.style.position = 'fixed';
                        ta.style.opacity = '0';
                        document.body.appendChild(ta);
                        ta.focus();
                        ta.select();
                        const success = document.execCommand('copy');
                        document.body.removeChild(ta);
                        success ? resolve() : reject();
                    } catch (err) {
                        reject(err);
                    }
                });

            copyPromise.then(() => {
                let badge = link.parentElement.querySelector('.email-copied-badge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'email-copied-badge inline-flex items-center text-xs font-bold text-[#f49a5b] bg-black/50 border border-[#f49a5b]/40 px-2.5 py-0.5 rounded-full transition-opacity duration-300 ml-2 shadow-sm';
                    link.parentElement.appendChild(badge);
                }
                badge.textContent = 'Copied ' + email;
                badge.style.opacity = '1';
                setTimeout(() => {
                    badge.style.opacity = '0';
                    setTimeout(() => badge.remove(), 300);
                }, 2500);
            }).catch(() => {});
        });
    });
});

// Header product search toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-kmg-search-toggle]');
    const panel = document.getElementById('kmg-search');
    if (!toggle || !panel) return;
    toggle.addEventListener('click', () => {
        const open = panel.classList.toggle('hidden') === false;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) panel.querySelector('input[type="search"]')?.focus();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !panel.classList.contains('hidden')) {
            panel.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.focus();
        }
    });
});

// Coupon toast: show once per browser session, copy code to clipboard
document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('kmg-promo-toast');
    if (!toast) return;
    let dismissed = false;
    try { dismissed = sessionStorage.getItem('kmgPromoDismissed') === '1'; } catch (e) {}
    if (dismissed) return;
    setTimeout(() => toast.classList.remove('hidden'), 2500);
    toast.querySelector('[data-kmg-toast-close]')?.addEventListener('click', () => {
        toast.classList.add('hidden');
        try { sessionStorage.setItem('kmgPromoDismissed', '1'); } catch (e) {}
    });
    const copyBtn = toast.querySelector('[data-kmg-copy]');
    copyBtn?.addEventListener('click', async () => {
        const label = copyBtn.querySelector('[data-kmg-copy-label]');
        try { await navigator.clipboard.writeText(copyBtn.dataset.kmgCopy); } catch (e) {}
        if (label && toast.dataset.copied) label.textContent = toast.dataset.copied;
    });
});

// Product card pack-size pills: swap the displayed price
document.addEventListener('click', (e) => {
    const pill = e.target.closest('[data-kmg-pill]');
    if (!pill) return;
    const card = pill.closest('[data-kmg-card]');
    if (!card) return;
    card.querySelectorAll('[data-kmg-pill]').forEach((el) => el.setAttribute('aria-pressed', el === pill ? 'true' : 'false'));
    const price = card.querySelector('.dynamic-price');
    if (price) price.innerHTML = pill.dataset.price;
});

// Horizontal carousels: prev/next buttons scroll by one card
document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-kmg-scroll]');
    if (!btn) return;
    const track = document.getElementById(btn.dataset.kmgScroll);
    if (!track) return;
    const card = track.firstElementChild;
    const step = card ? card.getBoundingClientRect().width + 24 : track.clientWidth * 0.8;
    track.scrollBy({ left: step * Number(btn.dataset.dir || 1), behavior: 'smooth' });
});
