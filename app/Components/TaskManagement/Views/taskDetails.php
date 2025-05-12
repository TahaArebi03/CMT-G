<?php
// File: /app/Components/TaskManagement/Views/taskDetails.php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';

// نفترض أن $task أرسله الـ Controller
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>تفاصيل المهمة | <?= htmlspecialchars($task->getTitle()) ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="taskDetails.css">
</head>
<body>
  <h1>📝 <?= htmlspecialchars($task->getTitle()) ?></h1>
  <p><strong>المشروع:</strong> #<?= htmlspecialchars($task->getProjectId()) ?></p>
  <p><strong>الوصف:</strong><br><?= nl2br(htmlspecialchars($task->getDescription())) ?></p>
  <p><strong>مسند إلى:</strong>
    <?= $task->getAssignedTo() 
        ? htmlspecialchars(User::findById($task->getAssignedTo())->getName()) 
        : 'غير مخصّص' ?>
  </p>
  <p><strong>الحالة:</strong> <?= htmlspecialchars(ucfirst($task->getStatus())) ?></p>
  <p><strong>الأولوية:</strong> <?= htmlspecialchars(ucfirst($task->getPriority())) ?></p>
  <p><strong>المهلة:</strong> <?= htmlspecialchars($task->getDeadline()) ?></p>

  <a href="../Controllers/TaskController.php?action=edit&id=<?= $task->getTaskId() ?>
                 &project_id=<?= $task->getProjectId() ?>"
     class="btn edit">✏️ تعديل المهمة</a>
  <a href="../Controllers/TaskController.php?action=list&project_id=<?= $task->getProjectId() ?>"
     class="btn back">🔙 العودة للقائمة</a>
</body>
</html>
