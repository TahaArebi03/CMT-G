<?php


require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../../UserManagement/Models/StudentUser.php';

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>Task Form</title>
  <link rel="stylesheet" href="../../../../public/css/taskForm.css">

</head>
<body>
  <h1>Task Form</h1>
  <form action="../Controllers/TaskController.php?action=create&project_id=<?= $project_id ?>" method="post">
    <label for="title">Title</label><br>
    <input type="text" id="title" name="title" required>

    <label for="description">Description</label><br>
    <textarea id="description" name="description" rows="4"></textarea><br><br>

    <label for="assigned_to">Assigned To</label><br>
    <select id="assigned_to" name="assigned_to" required>
      <option value="">-- Select Student --</option>
      <?php foreach ($students as $stu): ?>
        <option value="<?= $stu->getUserId() ?>">
          <?= htmlspecialchars($stu->getName()) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label for="status">Status</label><br>
    <select id="status" name="status">
      <?php foreach (['not_started','in_progress','completed'] as $st): ?>
        <option value="<?= $st ?>">
          <?= ucfirst(str_replace('_',' ',$st)) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label for="priority">Priority</label><br>
    <select id="priority" name="priority">
      <?php foreach (['high','medium','low'] as $pr): ?>
        <option value="<?= $pr ?>">
          <?= ucfirst($pr) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label for="deadline">Deadline</label><br>
    <input type="datetime-local" id="deadline" name="deadline"
      value=""><br><br>

    <button type="submit"> Save </button>
  </form>
</body>
</html>
