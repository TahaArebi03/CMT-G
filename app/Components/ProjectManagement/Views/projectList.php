<!DOCTYPE html>
<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../../UserManagement/Models/user.php';

// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $project = null;

// if (isset($_SESSION['user_id'])) {
//     $userId = $_SESSION['user_id'];

//     // جلب بيانات المستخدم
//     $user = User::findById($userId); 

//     if ($user) {
//         $projectId = $user->getProjectId();

//         // جلب المشروع
//         $project = Project::findById($projectId);
//     }
// }
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
      <a href="projectForm.php" class="add-btn">+ Add New Project</a>
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
