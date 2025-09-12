var currentYear = new Date().getFullYear();
var yearElement = document.getElementById('year');
if (yearElement) {
    yearElement.textContent = currentYear;
}

var menuButton = document.querySelector('.nav-toggle');
var mobileMenu = document.getElementById('site-nav');

if (menuButton && mobileMenu) {
    menuButton.addEventListener('click', function() {
        var isMenuOpen = mobileMenu.classList.contains('open');
        
        if (isMenuOpen) {
            mobileMenu.classList.remove('open');
            menuButton.setAttribute('aria-expanded', 'false');
        } else {
            mobileMenu.classList.add('open');
            menuButton.setAttribute('aria-expanded', 'true');
        }
    });
}