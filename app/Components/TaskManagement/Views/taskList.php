<?php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';

?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>قائمة المهام</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="taskList.css">
  
</head>
<body>
  <h1>📋 قائمة المهام للمشروع</h1>
  <a href="../Controllers/TaskController.php?action=create&project_id=<?= $project_id ?>" class="btn add">
    + إضافة مهمة
  </a>

  <?php if (empty($tasks)): ?>
    <p>لا توجد مهام بعد.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>العنوان</th>
          <th>مسند إلى</th>
          <th>الحالة</th>
          <th>المهلة</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($tasks as $t): 
        $assignee = $t->getAssignedTo()
          ? User::findById($t->getAssignedTo())->getName()
          : 'غير مخصّص';
      ?>
        <tr>
          <td><?= htmlspecialchars($t->getTitle()) ?></td>
          <td><?= htmlspecialchars($assignee) ?></td>
          <td><?= htmlspecialchars(ucfirst($t->getStatus())) ?></td>
          <td><?= htmlspecialchars($t->getDeadline()) ?></td>
          <td>
            <a href="../Controllers/TaskController.php?action=edit&id=<?= $t->getTaskId() ?>
                      &project_id=<?= $project_id ?>">تفاصيل</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
