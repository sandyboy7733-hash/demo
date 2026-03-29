<?php
// =============================================
// TITAN — Register Page
// register.php
// =============================================
session_start();
require_once 'db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check duplicate
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                $success = 'Account created! Redirecting to login…';
                header('Refresh: 2; url=login.php');
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register — TITAN</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

<nav class="navbar">
  <a href="index.html" class="nav-logo">TIT<span>AN</span></a>
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="contact.php">Contact</a></li>
  </ul>
  <div class="nav-actions">
    <a href="login.php" class="btn-nav btn-outline">Login</a>
  </div>
</nav>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <span class="logo-text">TITAN</span>
    </div>
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-sub">Already a member? <a href="login.php">Sign in</a></p>

    <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" action="register.php" novalidate>
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Your full name"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required/>
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Min 8 chars" required/>
        </div>
        <div class="form-group">
          <label for="confirm">Confirm Password</label>
          <input type="password" id="confirm" name="confirm" placeholder="Repeat password" required/>
        </div>
      </div>
      <div style="margin-bottom:1rem;font-size:0.8rem;color:var(--text)">
        <label style="display:flex;gap:0.6rem;align-items:flex-start;cursor:pointer">
          <input type="checkbox" required style="margin-top:3px;accent-color:var(--gold)"/>
          I agree to the <a href="#" style="color:var(--gold)">Terms of Service</a> and <a href="#" style="color:var(--gold)">Privacy Policy</a>
        </label>
      </div>
      <button type="submit" class="btn-full">Create My Account</button>
    </form>

    <!-- Password strength indicator (JS) -->
    <div id="strength-bar" style="height:3px;border-radius:2px;background:var(--border);margin-top:0.5rem;overflow:hidden">
      <div id="strength-fill" style="height:100%;width:0;transition:all 0.3s;border-radius:2px"></div>
    </div>
    <div id="strength-label" style="font-size:0.72rem;color:var(--text);margin-top:0.3rem;text-align:right"></div>
  </div>
</div>

<script>
document.getElementById('password').addEventListener('input', function(){
  const v = this.value;
  let score = 0;
  if (v.length >= 8)       score++;
  if (/[A-Z]/.test(v))     score++;
  if (/[0-9]/.test(v))     score++;
  if (/[^A-Za-z0-9]/.test(v)) score++;
  const colors  = ['#e74c3c','#e67e22','#f1c40f','#2ecc71'];
  const labels  = ['Weak','Fair','Good','Strong'];
  const fill    = document.getElementById('strength-fill');
  const label   = document.getElementById('strength-label');
  fill.style.width = (score / 4 * 100) + '%';
  fill.style.background = colors[score - 1] || 'transparent';
  label.textContent = score > 0 ? labels[score - 1] : '';
});
</script>
</body>
</html>
