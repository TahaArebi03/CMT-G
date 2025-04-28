<?php  
require_once __DIR__ . '../../../UserManagment/Models/User.php';
require_once __DIR__ . '../../Models/ProjectMember.php';




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
   
    </div>
    <a href="ProjectController.php?action=edit&id=<?= $project->getId() ?>"
       class="btn edit">✏️ Edit Project</a>
  </div>
  <h3>أعضاء المشروع</h3>
<table>
  <thead><tr><th>الاسم</th><th>الدور</th><th>إجراءات</th></tr></thead>
  <tbody>
  <?php foreach ($members as $m): ?>
    <?php $user= User::findById($m->getUserId());  ?>
    <tr>
      <td><?= htmlspecialchars($user->getName())?> </td>
      <td><?= htmlspecialchars($m->getRoleInProject()) ?></td>
      <td>
        <a href="ProjectMemberController.php?action=edit
                 &project_id=<?= $m->getProjectId() ?>
                 &user_id=<?= $m->getUserId() ?>">تعديل</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<a href="addMemberForm.php?project_id=<?= $project->getId() ?>"
   class="btn">+ إضافة عضو</a>

  
</body>
</html>
