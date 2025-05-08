<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';

?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>مهام المشروع</title>
</head>
<body>

<div class="tabs">
  <a href="projectDetails.php?id=<?= $project_id ?>">تفاصيل المشروع</a>
  <a href="projectMembers.php?project_id=<?= $project_id ?>">الأعضاء</a>
  <a href="projectTasks.php?project_id=<?= $project_id ?>">المهام</a>
</div>

<h3>مهام المشروع</h3>
<table>
  <thead>
    <tr><th>العنوان</th><th>مسند إلى</th><th>الحالة</th><th>الأولوية</th><th>المهلة</th></tr>
  </thead>
  <tbody>
    <?php foreach ($tasks as $task): ?>
      <tr>
        <td><?= htmlspecialchars($task->getTitle()) ?></td>
        <td><?= htmlspecialchars($task->getAssignedTo()) ?></td>
        <td><?= htmlspecialchars($task->getStatus()) ?></td>
        <td><?= htmlspecialchars($task->getPriority()) ?></td>
        <td><?= htmlspecialchars($task->getDeadline()) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<a href="TaskController.php?action=create&project_id=<?= $project_id ?>" class="btn">+ إضافة مهمة</a>

</body>
</html>
