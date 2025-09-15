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


// Dynamic Navigation based on login status
document.addEventListener('DOMContentLoaded', function() {
    updateNavigation();
});

async function updateNavigation() {
    try {
        const response = await fetch('backend/check_session.php');
        const session = await response.json();
        
        const nav = document.querySelector('.site-nav ul');
        if (!nav) return;
        
        // Get current page
        const currentPage = window.location.pathname.split('/').pop();
        
        if (session.logged_in) {
            // User is logged in - update navigation
            const newNav = `
                <li><a href="index.html" ${currentPage === 'index.html' ? 'aria-current="page"' : ''}>Home</a></li>
                <li><a href="about.html" ${currentPage === 'about.html' ? 'aria-current="page"' : ''}>About</a></li>
                <li><a href="services.php" ${currentPage === 'services.php' ? 'aria-current="page"' : ''}>Services</a></li>
                <li><a href="gallery.php" ${currentPage === 'gallery.php' ? 'aria-current="page"' : ''}>Gallery</a></li>
                <li><a href="contact.php" ${currentPage === 'contact.php' ? 'aria-current="page"' : ''}>Contact</a></li>
                ${session.role === 'user' ? `<li><a href="user-dashboard.php" ${currentPage === 'user-dashboard.php' ? 'aria-current="page"' : ''}>My Account</a></li>` : ''}
                ${session.role === 'admin' ? `<li><a href="backend/modules/admin/dashboard.php" target="_blank">Admin Panel</a></li>` : ''}
                <li><a href="backend/logout.php">Logout (${session.firstName})</a></li>
            `;
            nav.innerHTML = newNav;
        } else {
            // User is not logged in - default navigation
            const newNav = `
                <li><a href="index.html" ${currentPage === 'index.html' ? 'aria-current="page"' : ''}>Home</a></li>
                <li><a href="about.html" ${currentPage === 'about.html' ? 'aria-current="page"' : ''}>About</a></li>
                <li><a href="services.php" ${currentPage === 'services.php' ? 'aria-current="page"' : ''}>Services</a></li>
                <li><a href="gallery.php" ${currentPage === 'gallery.php' ? 'aria-current="page"' : ''}>Gallery</a></li>
                <li><a href="contact.php" ${currentPage === 'contact.php' ? 'aria-current="page"' : ''}>Contact</a></li>
                <li><a href="login.html" ${currentPage === 'login.html' ? 'aria-current="page"' : ''}>Login</a></li>
            `;
            nav.innerHTML = newNav;
        }
    } catch (error) {
        // If there's an error, keep default navigation
        console.log('Navigation update failed:', error);
    }
}


document.addEventListener('click', async function(e) {        
        // Delete contact submission
        if (e.target.classList.contains('delete-contact-btn')) {
            if (confirm('Are you sure you want to delete this contact submission?')) {
                const contactId = e.target.dataset.id;
                const formData = new FormData();
                formData.append('id', contactId);
                    
                try {
                    const response = await fetch('delete_contact.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                        
                if (result.success) {
                     location.reload();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('An error occurred');
            }
        }
    }
});