<?php
require __DIR__ . '../../../UserManagement/Controllers/UserController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Dashboard | MyApp</title>
  <link rel="stylesheet" href="../../../../public/css/userDashboard.css">
</head>
<body>
  <div class="dashboard-container">
    <header>
      <h1>Welcome, <?php echo htmlspecialchars($user->getName); ?></h1>
      <p>Your dashboard at a glance</p>
    </header>

    <div class="dashboard-links">
      <a href="../../ProjectManagement/Controllers/ProjectController.php?action=list">📁 My Project</a>
      <a href="#">👤 Profile</a>
      <a href="#">🚪 Logout</a>
    </div>

    <section class="dashboard-summary">
      <h2>Quick Stats</h2>
      <div class="stats">
        <div class="card">
          <h3>5</h3>
          <p>Active Projects</p>
        </div>
        <div class="card">
          <h3>12</h3>
          <p>Pending Tasks</p>
        </div>
        <div class="card">
          <h3>3</h3>
          <p>Notifications</p>
        </div>
      </div>
    </section>
  </div>
</body>
</html>
