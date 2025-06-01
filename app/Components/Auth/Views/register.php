<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | MyApp</title>
  <link rel="stylesheet" href="../../../../public/css/register.css">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <div class="register-container">
    <div class="register-header">
      <h2>Create Account</h2>
      <p>Join us by filling the information below</p>
    </div>

    <form method="POST" action="../Controllers/AuthController.php?action=register">
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <div class="input-group">
        <span class="input-icon">👤</span>
        <input type="text" name="name" placeholder="Full Name" required>
      </div>

      <div class="input-group">
        <span class="input-icon">✉️</span>

        <input type="email" name="email" placeholder="Email Address" required>

      </div>

      <div class="input-group">
        <span class="input-icon">🔒</span>
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <div class="input-group">
        <span class="input-icon">🔒</span>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      </div>

      <div class="radio-group">
        <h4>Account Type</h4>
        <div class="role-select">
          <div>
            <input type="radio" id="student" name="role" value="Student" checked>
            <label for="student">Student</label>
          </div>
          <div>
            <input type="radio" id="admin" name="role" value="Admin">
            <label for="admin">Admin</label>
          </div>
        </div>
      </div> <div class="radio-group">
        <h4>Preferred Language</h4>
        <div class="role-select">
          <input type="radio" id="arabic" name="language" value="ar" checked>
          <label for="arabic">Arabic</label>

          <input type="radio" id="english" name="language" value="en">
          <label for="english">English</label>
        </div>
      </div> <div class="major-select">
        <h4>Select Your Major</h4>
        <select name="major" required>
          <option value="" disabled selected>Select your major</option>
          <option value="computer_science">Computer Science</option>
          <option value="information_technology">Information Technology</option>
          <option value="software_engineering">Software Engineering</option>
          <option value="data_science">Data Science</option>
          <option value="networking">Networking</option>
          <option value="cyber_security">Cyber Security</option>
        </select> </div>

      <button type="submit" class="register-btn">
        <span>Register</span>
      </button>

      <div class="login-link">
        Already have an account? <a href="../Controllers/AuthController.php?action=login">Login here</a>
      </div>
    </form>
  </div>
</body>
</html>