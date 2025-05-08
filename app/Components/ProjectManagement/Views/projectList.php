<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My project</title>
  <link rel="stylesheet" href="../../../../public/css/projectList.css">
</head>
<body>
  <div class="container">
  <?php if (empty($project)): ?>
      <p class="empty">You dont have any project yet.</p>
      <a href="projectForm.html" class="add-btn">+ Add New Project</a>
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
