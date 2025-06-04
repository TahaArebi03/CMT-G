<!DOCTYPE html>
<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../../UserManagement/Models/user.php';

// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My project</title>
  <link rel="stylesheet" href="../../../../public/css/projectList.css">
</head>
<body>
  <div class="container">
  <?php if (empty($project)): ?>
      <p class="empty">You don't have any project yet.</p>
      <?php if($user->getRole() === 'Admin'): ?>
      <a href="ProjectController.php?action=create" class="add-btn">+ Add New Project</a>
      <?php else: ?>
        <a href="#" class="add-btn">Join</a>
      <?php endif; ?>
  <?php else: ?>
    <h1>My project</h1>
    <ul class="pm-list">
        <li class="pm-item">
          <h2><?= htmlspecialchars($project->getTitle()) ?></h2>
          <a href="ProjectController.php?action=view&id=<?= $project->getId()?>" 
            class="btn view">View Details</a>
        </li>
    </ul>
  <?php endif; ?>
  </div>
</body>
</html>
