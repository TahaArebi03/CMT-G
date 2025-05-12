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
  <style>
    /* Base RTL Styles */
body {
  font-family: 'Tahoma', 'Arial', sans-serif;
  background-color: #f5f7fa;
  margin: 0;
  padding: 20px;
  color: #333;
  direction: rtl;
}

/* Container */
.task-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Header */
.task-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
}

.task-title {
  color: #2c3e50;
  font-size: 1.8rem;
  margin: 0;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  border-radius: 6px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}

.btn-primary {
  background-color: #3498db;
  color: white;
}

.btn-primary:hover {
  background-color: #2980b9;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 40px 20px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-top: 20px;
}

.empty-icon {
  width: 150px;
  height: auto;
  margin-bottom: 20px;
  opacity: 0.7;
}

.empty-title {
  color: #2c3e50;
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.empty-message {
  color: #7f8c8d;
  font-size: 1.1rem;
  margin-bottom: 25px;
}

/* Table Styles */
.task-table-container {
  overflow-x: auto;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  padding: 1px;
}

.task-table {
  width: 100%;
  border-collapse: collapse;
}

.task-table th {
  background-color: #f8f9fa;
  color: #2c3e50;
  padding: 15px;
  font-weight: 600;
  text-align: right;
  border-bottom: 2px solid #eee;
}

.task-table td {
  padding: 15px;
  border-bottom: 1px solid #eee;
  text-align: right;
}

.task-table tr:last-child td {
  border-bottom: none;
}

.task-table tr:hover {
  background-color: #f8f9fa;
}

/* Status Badges */
.status-badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-pending {
  background-color: #fff3cd;
  color: #856404;
}

.status-in-progress {
  background-color: #cce5ff;
  color: #004085;
}

.status-completed {
  background-color: #d4edda;
  color: #155724;
}

.status-cancelled {
  background-color: #f8d7da;
  color: #721c24;
}

/* Action Button */
.btn-action {
  color: #3498db;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s;
}

.btn-action:hover {
  color: #2980b9;
  text-decoration: underline;
}

/* Text Alignment */
.text-right {
  text-align: right;
}

.text-center {
  text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
  .task-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .task-title {
    font-size: 1.5rem;
  }
  
  .task-table th, 
  .task-table td {
    padding: 10px 5px;
    font-size: 0.9rem;
  }
  
  .btn {
    width: 100%;
  }
}
  </style>
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
      <?php foreach (['not_started','in_progress','completed','in_review'] as $st): ?>
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
