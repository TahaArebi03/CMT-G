<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | MyApp</title>
  <link rel="stylesheet" href="../../../../public/css/login.css">
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <h2>Login to Your Account</h2>
      <p>Enter your credentials to access your dashboard</p>
    </div>

    <form method="POST" action="../Controllers/AuthController.php?action=login">
      <?php if (!empty($error)): ?>
    <div class="error-message">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

      <div class="input-group">
        <span class="input-icon">✉️</span>
        <input type="email" name="email" placeholder="Email Address" required>
      </div>

      <div class="input-group">
        <span class="input-icon">🔒</span>
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <button type="submit" class="login-btn">
        <span>Login</span>
      </button>

      <div class="register-link">
        Don't have an account? <a href="register.php">Create one here</a>
      </div>
    </form>
  </div>
</body>
</html>
