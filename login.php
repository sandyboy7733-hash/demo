<?php
// =============================================
// TITAN — Login Page (FIXED)
// =============================================
session_start();
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } else {

        // ✅ FIXED QUERY (username instead of name, removed role)
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {

            // ✅ FIXED bind_result
            $stmt->bind_result($id, $username, $db_password);
            $stmt->fetch();

            // ✅ SIMPLE PASSWORD CHECK (since you stored plain password)
            if ($password === $db_password) {

                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $username;

                $success = "Welcome back, $username!";
                header("Refresh: 2; url=index.html");

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

    <!-- ERROR MESSAGE -->
    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- SUCCESS MESSAGE -->
    <?php if ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <button type="submit" class="btn-full">Login</button>
    </form>

  </div>
</div>

</body>
</html>