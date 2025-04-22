<?php  
require_once __DIR__ . '../../../UserManagment/Models/User.php';
// createdBy = user_id ->session
// getName for created project
$creator= User::findById($project->getCreatedBy());
// controller--viewAction
$project= Project::findById($id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Project Details | <?= htmlspecialchars($project->getTitle()) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="projectDetails.css">
</head>
<body>
  <div class="pd-container">
    <h1>🚀 <?= htmlspecialchars($project->getTitle()) ?></h1>
    <div class="pd-info">
      <p><strong>Description:</strong>
        <?= nl2br(htmlspecialchars($project->getDescription())) ?>
      </p>
      <p><strong>Objectives:</strong>
        <?= nl2br(htmlspecialchars($project->getObjectives())) ?>
      </p>
      <p><strong>Deadline:</strong>
        <?= htmlspecialchars($project->getDeadline()) ?>
      </p>
      <p><strong>Status:</strong>
        <?php $st = $project->getStatus(); ?>
        <span class="status <?= $st === 'active' ? 'active' : 'archived' ?>">
          <?= ucfirst($st) ?>
        </span>
      </p>
      <p><strong>Created By:</strong>
        <?= htmlspecialchars($creator?->getName() ?? 'Unknown') ?>
      </p>
    </div>
    <a href="ProjectController.php?action=edit&id=<?= $project->getId() ?>"
       class="btn edit">✏️ Edit Project</a>
  </div>
</body>
</html>
