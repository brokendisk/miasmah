export function initNavigation() {
    const toggles = document.querySelectorAll('.hamburger');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', e => {
            e.preventDefault();
            if (toggle.classList.contains('is-active')) {
                toggle.classList.remove('is-active');
                $('.nav').removeClass('mobileNav-active');
                $('.overlay').fadeOut();
            } else {
                toggle.classList.add('is-active');
                $('.overlay').fadeIn();
                $('.nav').addClass('mobileNav-active');
            }
        });
    });
}
