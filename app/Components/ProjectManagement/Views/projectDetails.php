<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../../UserManagement/Models/StudentUser.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';
require_once __DIR__ . '/../../TaskManagement/Models/Task.php';
require_once __DIR__ . '/../Models/ProjectMember.php';
?>
<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="UTF-8">
  <title>Project Details | <?= htmlspecialchars($project->getTitle()) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../../public/css/projectDetails.css">
  
</head>


<body>
<div class="tabs">
  <a href="#">تفاصيل المشروع</a>
  <a href="../Controllers/ProjectMemberController.php?action=list&project_id= <?= $project->getId() ?>">الأعضاء</a>
  <a href="../../TaskManagement/Controllers/TaskController.php?action=list&project_id=<?= $project->getId() ?>">المهام</a>
  <a href="../../Voting/Controllers/VoteController.php?action=list&project_id=<?= $project->getId() ?>">التصويتات</a>
</div>
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
   
    </div>
 

    <a href="ProjectController.php?action=edit&id=<?= $project->getId() ?>"
       class="btn edit">✏️ Edit Project</a>
  </div>

  