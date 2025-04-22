
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Project List</title>
  <link rel="stylesheet" href="../../../../public/css/projectList.css">
</head>
<body>
  <div class="container">
    <h1>Project List</h1>
    <a href="projectForm.html" class="add-btn">+ Add New Project</a>
    <ul class="pm-list">
       // controller -> listAction
      <?php foreach ($projects as $p): ?>
        <li class="pm-item">
          <h2><?= htmlspecialchars($p['title']) ?></h2>
          <a href="ProjectController.php?action=view&id=<?= $p['project_id'] ?>"
            class="btn view">View Details</a>
        </li>
      <?php endforeach; ?>
</ul>
  </div>
</body>
</html>
