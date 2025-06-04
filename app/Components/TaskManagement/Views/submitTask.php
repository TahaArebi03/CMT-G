<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';
// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسليم المهمة</title>
    <link rel="stylesheet" href="../../../../public/css/task.css">
</head>
<body>
<div class="container">
    <a href="../Controllers/TaskController.php?action=list&project_id=<?= $task->getProjectId() ?>" 
    class="btn back">🔙 العودة إلى قائمة المهام</a>
<h2>📤 تسليم المهمة: <?= htmlspecialchars($task->getTitle()) ?></h2>

<form action="../Controllers/TaskController.php?action=upload&task_id=<?= $task->getTaskId() ?>" method="POST" enctype="multipart/form-data">
    <label>اختر الملف المطلوب رفعه:</label><br>
 
    <input type="file" name="submission_file" required><br><br>
    <button type="submit">📨 رفع وتسليم</button>
</form>
</div>
</body>
</html>
