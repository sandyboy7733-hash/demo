<?php
// =============================================
// TITAN — Login Page
// login.php
// =============================================
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $hash, $role);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['user_id']   = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = $role;
                $success = "Welcome back, $name! Redirecting…";
                header('Refresh: 2; url=index.html');
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'No account found with that email.';
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
  <title>Login — TITAN</title>
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
    <a href="register.php" class="btn-nav btn-solid">Register</a>
  </div>
</nav>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <span class="logo-text">TITAN</span>
    </div>
    <h2 class="auth-title">Welcome Back</h2>
    <p class="auth-sub">New here? <a href="register.php">Create an account</a></p>

    <?php if ($error):   ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required/>
      </div>
      <div style="display:flex;justify-content:flex-end;margin:-0.5rem 0 1rem">
        <a href="#" style="font-size:0.8rem;color:var(--gold)">Forgot password?</a>
      </div>
      <button type="submit" class="btn-full">Sign In</button>
    </form>

    <div class="form-divider">or continue with</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem">
      <button class="btn-secondary" style="justify-content:center;padding:0.75rem">
        🔵 Google
      </button>
      <button class="btn-secondary" style="justify-content:center;padding:0.75rem">
        ⬛ Apple
      </button>
    </div>
  </div>
</div>

</body>
</html>
