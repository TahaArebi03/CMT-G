<?php
// File: myProjects.php
// يفترض أن $projects معرّفة من الـ Controller

// (اختياري) تضمين رأس موحد أو قائمة تنقل:
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Project</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="myProjects.css">
</head>
<body>
  <div class="mp-container">
    <header>
      <h1>🏷️ My Project</h1>
      <a href="UserController.php?action=dashboard" class="btn back">← Dashboard</a>
    </header>

    <?php if (empty($project)): ?>
      <p class="empty">You are not a member of any project yet.</p>
    <?php else: ?>
      <ul class="mp-list">
          <li>
            <h2><?= htmlspecialchars($project['title']) ?></h2>
            <p><?= htmlspecialchars($project['description']) ?>…</p>
            <a href="../../ProjectManagement/Controllers/ProjectController.php?action=view
                      &id=<?= $p['project_id'] ?>"
               class="btn view">View</a>
          </li>
        
      </ul>
    <?php endif; ?>
  </div>
</body>
</html>
