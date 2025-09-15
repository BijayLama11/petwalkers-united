<?php
// Fetch gallery images from database
require_once 'backend/config/db_config.php';

$sql = "SELECT id, image_url, caption, upload_date FROM gallery_images ORDER BY upload_date DESC";
$result = $conn->query($sql);
$galleryImages = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $galleryImages[] = $row;
    }
}
$conn->close();
?>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gallery | PetWalkers United</title>
    <meta
      name="description"
      content="Media gallery of our walks and sits with interactive previews."
    />
    <link rel="icon" type="image/x-icon" href="img/logo.svg" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/gallery.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
  </head>
  <body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header" role="banner">
      <div class="container header-inner">
        <a class="brand" href="/" aria-label="PetWalkers United home">
          <img src="img/logo.svg" alt="" width="44" height="44" />
          <span class="brand-text">PetWalkers United</strong></span>
        </a>
        <button
          class="nav-toggle"
          aria-controls="site-nav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="nav-toggle-bar" aria-hidden="true"></span>
        </button>
        <nav id="site-nav" class="site-nav" aria-label="Primary">
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="gallery.php" aria-current="page">Gallery</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="login.html" class="active">Log In</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main id="main">
      <section class="heading-content">
        <div class="container">
          <h1>Media Gallery</h1>
          <p class="lead">
            See our furry friends in action. Click any photo to view larger
            images of happy pets during their walks and visits.
          </p>
        </div>
      </section>

      <section class="gallery-intro">
        <div class="container">
          <div class="gallery-intro-content">
            <p>
              Every walk and visit is an opportunity for joy, exercise, and
              bonding. Here's a glimpse of the happy moments we share with our
              four-legged clients.
            </p>
          </div>
        </div>
      </section>

      <section class="container" aria-label="Photo grid">
        <?php if (count($galleryImages) > 0): ?>
          <ul class="thumb-grid" role="list">
            <?php foreach ($galleryImages as $image): ?>
              <li>
                <button
                  class="thumb"
                  data-full="<?php echo htmlspecialchars($image['image_url']); ?>"
                  data-caption="<?php echo htmlspecialchars($image['caption']); ?>"
                  aria-label="Open larger image: <?php echo htmlspecialchars($image['caption']); ?>"
                >
                  <img
                    src="<?php echo htmlspecialchars($image['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($image['caption']); ?>"
                  />
                </button>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="no-images" style="text-align: center; padding: 4rem 0;">
            <h2 style="color: var(--muted); margin-bottom: 1rem;">No Images Yet</h2>
            <p style="color: var(--muted); font-size: 1.1rem;">
              We're building our gallery! Check back soon to see photos of our happy pets.
            </p>
          </div>
        <?php endif; ?>
      </section>

      <!-- Modal viewer -->
      <div
        class="lightbox"
        role="dialog"
        aria-modal="true"
        aria-labelledby="lb-title"
        hidden
      >
        <div class="lightbox-backdrop" data-close></div>
        <figure
          class="lightbox-content"
          role="group"
          aria-label="Expanded media"
        >
          <img id="lb-img" src="" alt="Expanded gallery image" />
          <figcaption id="lb-title">Caption</figcaption>
          <button
            class="lb-close"
            type="button"
            aria-label="Close viewer"
            data-close
          >
            &times;
          </button>
        </figure>
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
          <a href="mailto:hello@petwalkersunited.example.au"
            >hello@petwalkersunited.example.au</a
          >
          <a href="tel:+61414408871"
            ><i class="fa fa-phone"></i> 0414 408 871</a
          >
          <div class="social" role="group" aria-label="Social media">
            <a href="#" aria-label="Follow on Instagram"
              ><i class="fa fa-instagram"></i> Instagram</a
            >
            <a href="#" aria-label="Follow on Facebook"
              ><i class="fa fa-facebook"></i> Facebook</a
            >
          </div>
        </address>
        <small
          >&copy; <span id="year"></span> PetWalkers United. All rights
          reserved.</small
        >
      </div>
    </footer>

    <script src="js/main.js" defer></script>
    <script src="js/gallery.js" defer></script>
  </body>
</html>
