<?php
// =============================================
// TITAN — Contact Page
// contact.php
// =============================================
require_once 'db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Name, email, and message are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $success = "Thank you, $name! We'll get back to you within 24 hours.";
        } else {
            $error = 'Something went wrong. Please try again.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact — TITAN</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

<nav class="navbar">
  <a href="index.html" class="nav-logo">TIT<span>AN</span></a>
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="contact.php" class="active">Contact</a></li>
  </ul>
  <div class="nav-actions">
    <a href="login.php"    class="btn-nav btn-outline">Login</a>
    <a href="register.php" class="btn-nav btn-solid">Register</a>
  </div>
</nav>

<div class="contact-page">
  <div style="text-align:center;margin-bottom:4rem">
    <span class="section-label">Get in Touch</span>
    <h1 style="font-size:clamp(2rem,5vw,3.5rem);margin-top:0.5rem">We'd Love to Hear<br>From <em style="font-style:normal;color:var(--gold)">You</em></h1>
    <div class="divider" style="margin:1.2rem auto"></div>
  </div>

  <div class="contact-grid">
    <!-- INFO COLUMN -->
    <div class="contact-info">
      <h2>Let's Talk</h2>
      <p>Whether you have a question about a product, need help with an order, or simply want to say hello — our team is here for you.</p>
      <div class="contact-items">
        <div class="contact-item">
          <div class="contact-icon">📍</div>
          <div class="contact-text">
            <strong>Visit Us</strong>
            <span>24 Pearl Street, Tirunelveli, Tamil Nadu 627001</span>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">📞</div>
          <div class="contact-text">
            <strong>Call Us</strong>
            <span>+91 98765 43210 (Mon–Sat, 9am–6pm)</span>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">✉️</div>
          <div class="contact-text">
            <strong>Email Us</strong>
            <span>hello@titan-store.in</span>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">⚡</div>
          <div class="contact-text">
            <strong>Response Time</strong>
            <span>Within 24 hours on business days</span>
          </div>
        </div>
      </div>

      <!-- Map placeholder -->
      <div style="margin-top:2.5rem;border-radius:10px;overflow:hidden;border:1px solid var(--border);aspect-ratio:4/3;background:var(--mid);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.8rem">
        <span style="font-size:2.5rem">🗺️</span>
        <span style="font-size:0.85rem;color:var(--text)">Map — Tirunelveli, Tamil Nadu</span>
      </div>
    </div>

    <!-- FORM COLUMN -->
    <div class="contact-form-card">
      <h3>Send a Message</h3>

      <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

      <form method="POST" action="contact.php" novalidate>
        <div class="form-row">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Full name"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required/>
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
          </div>
        </div>
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="How can we help?"
                 value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"/>
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" placeholder="Tell us everything…" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn-full">Send Message &nbsp;→</button>
      </form>
    </div>
  </div>

  <!-- FAQ STRIP -->
  <div style="margin-top:5rem">
    <div class="section-header" style="margin-bottom:2.5rem">
      <span class="section-label">Quick Answers</span>
      <h2>Frequently Asked</h2>
      <div class="divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.2rem">
      <?php
      $faqs = [
        ['What is your return policy?', '30-day hassle-free returns on all items in original condition.'],
        ['Do you ship internationally?', 'Yes! We ship to 40+ countries. Rates and delivery times vary.'],
        ['Are all products genuine?', '100% authentic goods sourced directly from verified manufacturers.'],
        ['How do I track my order?', 'You\'ll receive a tracking link via email once your order ships.'],
      ];
      foreach ($faqs as [$q, $a]): ?>
      <div style="background:var(--mid);border:1px solid var(--border);border-radius:10px;padding:1.5rem">
        <h4 style="font-size:0.95rem;margin-bottom:0.6rem;color:var(--white)"><?= htmlspecialchars($q) ?></h4>
        <p style="font-size:0.85rem"><?= htmlspecialchars($a) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer style="margin-top:5rem">
  <div class="footer-grid">
    <div class="footer-brand">
      <span class="logo-text">TITAN</span>
      <p>Curated luxury goods for the discerning individual.</p>
    </div>
    <div class="footer-col"><h4>Shop</h4><ul><li><a href="products.php">All Products</a></li></ul></div>
    <div class="footer-col"><h4>Help</h4><ul><li><a href="contact.php">Contact Us</a></li></ul></div>
    <div class="footer-col"><h4>Account</h4><ul><li><a href="login.php">Login</a></li><li><a href="register.php">Register</a></li></ul></div>
  </div>
  <div class="footer-bottom">
    <span>© 2025 TITAN. All rights reserved.</span>
  </div>
</footer>

<script>
window.addEventListener('scroll', () => {
  document.querySelector('.navbar').style.padding =
    window.scrollY > 60 ? '0.85rem 5%' : '1.2rem 5%';
});
</script>
</body>
</html>
