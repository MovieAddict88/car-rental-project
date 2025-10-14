// Custom JavaScript for the Car Rental Management System

// This file can be used to add interactivity to the site, such as:
// - Client-side form validation
// - Dynamic content loading with AJAX
// - Interactive maps for pickup locations
// - Custom animations and effects

document.addEventListener('DOMContentLoaded', function() {
    console.log('CRMS JavaScript is loaded and running.');

    // Example: Add a class to the body to indicate JS is enabled
    document.body.classList.add('js-enabled');

    // Public navbar hamburger/offcanvas
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const offcanvas = document.getElementById('offcanvasNav');
    const overlay = document.getElementById('offcanvasOverlay');
    const hamburgerIcon = hamburgerBtn ? hamburgerBtn.querySelector('.hamburger') : null;

    function closeOffcanvas() {
        if (offcanvas) offcanvas.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        if (hamburgerIcon) hamburgerIcon.classList.remove('active');
        if (hamburgerBtn) hamburgerBtn.setAttribute('aria-expanded', 'false');
        if (offcanvas) offcanvas.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function openOffcanvas() {
        if (offcanvas) offcanvas.classList.add('active');
        if (overlay) overlay.classList.add('active');
        if (hamburgerIcon) hamburgerIcon.classList.add('active');
        if (hamburgerBtn) hamburgerBtn.setAttribute('aria-expanded', 'true');
        if (offcanvas) offcanvas.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    if (hamburgerBtn && offcanvas && overlay) {
        hamburgerBtn.addEventListener('click', () => {
            const isOpen = offcanvas.classList.contains('active');
            if (isOpen) closeOffcanvas(); else openOffcanvas();
        });
        overlay.addEventListener('click', closeOffcanvas);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeOffcanvas();
        });
        // Close when clicking nav links on small screens
        offcanvas.addEventListener('click', (e) => {
            if (e.target.tagName === 'A') closeOffcanvas();
        });
    }
});