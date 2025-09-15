<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

// Only allow users (not admins) to access this page
if ($_SESSION['role'] !== 'user') {
    header("Location: backend/modules/admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Account | PetWalkers United</title>
    <meta name="description" content="Manage your PetWalkers United account and bookings." />
    <link rel="icon" type="image/x-icon" href="img/logo.svg" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/user-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header" role="banner">
        <div class="container header-inner">
            <a class="brand" href="/" aria-label="PetWalkers United home">
                <img src="img/logo.svg" alt="" width="44" height="44" />
                <span class="brand-text">PetWalkers United</span>
            </a>
            <button class="nav-toggle" aria-controls="site-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="nav-toggle-bar" aria-hidden="true"></span>
            </button>
            <nav id="site-nav" class="site-nav" aria-label="Primary">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="user-dashboard.php" aria-current="page">My Account</a></li>
                    <li><a href="backend/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main">
        <section class="heading-content">
            <div class="container">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</h1>
                <p class="lead">
                    Manage your pet care services and account information.
                </p>
            </div>
        </section>

        <div class="container user-dashboard">
            <div class="dashboard-grid">
                <!-- Quick Actions -->
                <section class="dashboard-card">
                    <h2><i class="fa fa-calendar"></i> Quick Actions</h2>
                    <div class="quick-actions">
                        <a href="contact.php" class="btn btn-primary">Book New Service</a>
                        <a href="services.php" class="btn btn-outline">View All Services</a>
                        <a href="gallery.php" class="btn btn-outline">View Gallery</a>
                    </div>
                </section>

                <!-- Account Information -->
                <section class="dashboard-card">
                    <h2><i class="fa fa-user"></i> Account Information</h2>
                    <div class="account-info">
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Account Type:</span>
                            <span class="info-value info-badge user">User Account</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Member Since:</span>
                            <span class="info-value">Recently Joined</span>
                        </div>
                    </div>
                </section>

                <!-- Coming Soon Features -->
                <section class="dashboard-card">
                    <h2><i class="fa fa-clock-o"></i> Coming Soon</h2>
                    <div class="coming-soon">
                        <div class="feature-preview">
                            <h3>📅 Booking History</h3>
                            <p>View all your past and upcoming appointments</p>
                        </div>
                        <div class="feature-preview">
                            <h3>🐕 Pet Profiles</h3>
                            <p>Manage your pet's information and preferences</p>
                        </div>
                        <div class="feature-preview">
                            <h3>📱 Real-time Updates</h3>
                            <p>Get live notifications about your pet's activities</p>
                        </div>
                        <div class="feature-preview">
                            <h3>💳 Payment Management</h3>
                            <p>View invoices and manage payment methods</p>
                        </div>
                    </div>
                </section>

                <!-- Contact Support -->
                <section class="dashboard-card">
                    <h2><i class="fa fa-support"></i> Need Help?</h2>
                    <div class="support-options">
                        <p>Our team is here to help with any questions about your pet care services.</p>
                        <div class="support-links">
                            <a href="contact.php" class="support-link">
                                <i class="fa fa-envelope"></i>
                                <span>Send Message</span>
                            </a>
                            <a href="tel:+61414408871" class="support-link">
                                <i class="fa fa-phone"></i>
                                <span>Call Us</span>
                            </a>
                            <a href="mailto:hello@petwalkersunited.example.au" class="support-link">
                                <i class="fa fa-envelope-o"></i>
                                <span>Email Us</span>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="container footer-inner">
            <div>
                <a class="brand small" href="/">
                    <img src="img/logo.svg" alt="" width="32" height="32" />
                    <span class="brand-text">PetWalkers United</span>
                </a>
                <p class="foot-tag">Local dog walking &amp; pet sitting services.</p>
            </div>
            <address class="foot-contact" aria-label="Contact details">
                <a href="mailto:hello@petwalkersunited.example.au">hello@petwalkersunited.example.au</a>
                <a href="tel:+61414408871"><i class="fa fa-phone"></i> 0414 408 871</a>
                <div class="social" role="group" aria-label="Social media">
                    <a href="#" aria-label="Follow on Instagram"><i class="fa fa-instagram"></i> Instagram</a>
                    <a href="#" aria-label="Follow on Facebook"><i class="fa fa-facebook"></i> Facebook</a>
                </div>
            </address>
            <small>&copy; <span id="year"></span> PetWalkers United. All rights reserved.</small>
        </div>
    </footer>

    <script src="js/main.js" defer></script>
</body>
</html>