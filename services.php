<?php
// backend/get_services.php
require_once 'backend/config/db_config.php';

// Prepare and execute a query to fetch all services
$sql = "SELECT id, service_name, subtitle, description, price FROM services ORDER BY price ASC";
$result = $conn->query($sql);

$services = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $services[] = $row;
  }
}
$conn->close();
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Services | PetWalkers United</title>
  <meta name="description" content="Dog walking and pet sitting service options and pricing." />
  <link rel="icon" type="image/x-icon" href="img/logo.svg" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/services.css" />
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
          <li><a href="services.php" aria-current="page">Services</a></li>
          <li><a href="gallery.html">Gallery</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main id="main">
    <section class="heading-content">
      <div class="container">
        <h1>Services &amp; Pricing</h1>
        <p class="lead">
          Transparent options for every routine. All visits include water
          refresh, basic cleanup, and a quick update.
        </p>
      </div>
    </section>

    <section class="container grid-3 card-grid" aria-label="Service cards">
      <?php if (count($services) > 0): ?>
        <?php foreach ($services as $service): ?>
          <article class="card service">
            <h2><?php echo htmlspecialchars($service['service_name']); ?></h2>
            <p class="muted"><?php echo htmlspecialchars($service['subtitle']); ?></p>
            <ul>
              <?php
              // Split the description by line breaks
              $items = explode(PHP_EOL, $service['description']);
              foreach ($items as $item) {
                // Skip any empty lines that might result from the split
                if (trim($item) !== '') {
                  echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
                }
              }
              ?>
            </ul>
            <p class="price">$<?php echo htmlspecialchars($service['price']); ?></p>
            <a class="btn btn-primary" href="contact.html">Book</a>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No services found. Please add services from the admin panel.</p>
      <?php endif; ?>
    </section>

    <section class="container faq" aria-label="FAQs">
      <h2>FAQs</h2>
      <details>
        <summary>Do you walk in rain?</summary>
        <p>
          Light rain? yes. In extreme weather we switch to indoor enrichment
          and potty breaks.
        </p>
      </details>
      <details>
        <summary>How do keys and alarms work?</summary>
        <p>
          Keys are coded, stored securely, and never labeled with your
          address. Alarm workflows set during meet &amp; greet.
        </p>
      </details>
      <details>
        <summary>What if my pet is anxious or reactive?</summary>
        <p>
          We specialize in nervous pets! We go at their pace, use positive
          reinforcement, and can provide desensitization exercises to build
          confidence over time.
        </p>
      </details>
      <details>
        <summary>Do you offer holiday or weekend services?</summary>
        <p>
          Yes. Holiday rates apply during major holidays (Christmas, New
          Year's, etc.). Weekend bookings are available at standard rates with
          advance notice.
        </p>
      </details>
      <details>
        <summary>What happens if you're sick or unavailable?</summary>
        <p>
          We maintain backup coverage with trusted local sitters who are
          familiar with our standards. You'll always receive advance notice
          and meet any backup sitter beforehand.
        </p>
      </details>
      <details>
        <summary>Can you administer medications?</summary>
        <p>
          We can give oral medications (pills, liquids) and topical
          treatments. We cannot administer injections or complex medical
          procedures. These require veterinary supervision.
        </p>
      </details>
      <details>
        <summary>How far in advance should I book?</summary>
        <p>
          Regular weekly services: 1-2 weeks notice preferred. One-off visits:
          48-72 hours. Holiday periods: 2-3 weeks advance booking recommended.
        </p>
      </details>
      <details>
        <summary>Do you provide overnight pet sitting?</summary>
        <p>
          Currently we focus on drop-in visits rather than overnight stays.
          For extended care needs, we can arrange multiple daily visits to
          maintain your pet's routine.
        </p>
      </details>
      <details>
        <summary>What areas do you service?</summary>
        <p>
          We cover a 5km radius from the local town center. Contact us to
          confirm if your address falls within our service area. We're always
          expanding based on demand.
        </p>
      </details>
    </section>
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
      <small>&copy; <span id="year"></span> PetWalkers United. All rights
        reserved.</small>
    </div>
  </footer>

  <script src="assets/js/main.js" defer></script>
</body>

</html>