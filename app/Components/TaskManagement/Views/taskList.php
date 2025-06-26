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
  <link rel="stylesheet" href="../../../../public/css/taskList.css">
 
</head>
<body>
  <div class="container">
    <h1>📋 قائمة المهام للمشروع</h1>
    <div class="page-actions">
        <a href="../../ProjectManagement/Controllers/ProjectController.php?action=view&id=<?= $project_id ?>" class="back-link">← العودة إلى المشروع</a>
        <?php if ($user->getRole() === 'Admin'): ?>
            <a href="../Controllers/TaskController.php?action=create&project_id=<?= $project_id ?>" class="add-task-btn">
                + إضافة مهمة
            </a>
        <?php endif; ?>
    </div>

  <?php if (empty($tasks)): ?>
    <p class="no-tasks-message">لا توجد مهام مضافة لهذا المشروع حتى الآن.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>العنوان</th>
          <th>مسند إلى</th>
          <th>الحالة</th>
          <th>المهلة</th>
          <th>الأولوية</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($tasks as $t): ?>
 <tr>
                        <td data-label="العنوان"><?= htmlspecialchars($t->getTitle()) ?></td>
                        <td data-label="مسند إلى"><?= htmlspecialchars($assigneeNames[$t->getTaskId()] ?? 'غير معين') ?></td>
                        <td data-label="الحالة">
                            <span >
                                <?= htmlspecialchars(ucfirst($t->getStatus())) ?>
                            </span>
                        </td>
                        <td data-label="الأولوية">
                            <span class="priority priority-<?= strtolower(htmlspecialchars($t->getPriority())) ?>">
                                <?= htmlspecialchars(ucfirst($t->getPriority())) ?>
                            </span>
                        </td>
                        <td data-label="المهلة"><?= htmlspecialchars($t->getDeadline()) ?></td>
                        <td data-label="إجراءات">
                          <?php if ($t->getAssignedTo()==$user_id): ?>
                            <a href="../Controllers/TaskController.php?action=submit&task_id=<?= $t->getTaskId() ?>&project_id=<?= $project_id ?>" class="action-link">بدء</a>
                          <?php endif; ?>
                            <a href="../Controllers/CommentController.php?action=list&task_id=<?=$t->getTaskId()?>
                            &user_id=<?=$t->getAssignedTo()?>&project_id=<?=$project_id?>" class="action-link">تعليقات</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
 <style>
   :root {
  --primary: #007bff;
  --secondary: #6c757d;
  --success: #28a745;
  --danger: #dc3545;
  --warning: #ffc107;
  --info: #17a2b8;
  --light: #f8f9fa;
  --dark: #343a40;
  --white: #ffffff;
  --font-family: 'Cairo', sans-serif;
  --border-radius: 8px;
  --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

body {
  font-family: var(--font-family);
  background-color: #f4f7f9;
  color: var(--dark);
  margin: 0;
  direction: rtl;
}

.container {
  padding: 20px 30px;
  max-width: 1200px;
  margin: 20px auto;
}

h1 {
  color: var(--dark);
  text-align: center;
  margin-bottom: 1.5rem;
  font-weight: 700;
}

.page-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 15px;
}

.back-link {
  color: var(--secondary);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}
.back-link:hover {
  color: var(--primary);
}

.add-task-btn {
  background-color: var(--primary);
  color: var(--white);
  padding: 10px 20px;
  border-radius: var(--border-radius);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}
.add-task-btn:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
}

.no-tasks-message {
  text-align: center;
  font-size: 1.2rem;
  color: var(--secondary);
  background-color: var(--white);
  padding: 40px;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
}

.table-container {
  background-color: var(--white);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  overflow: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead tr {
  background-color: #f1f5f9;
}

th {
  padding: 15px;
  text-align: right;
  font-weight: 700;
  color: #475569;
  font-size: 0.9rem;
  text-transform: uppercase;
}

td {
  padding: 15px;
  border-top: 1px solid #e2e8f0;
  vertical-align: middle;
}

tbody tr:hover {
  background-color: #f8fafc;
}

/* Status & Priority Badges */
.status, .priority {
  padding: 5px 12px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 0.85em;
  color: var(--white);
  text-transform: capitalize;
  display: inline-block;
  min-width: 80px;
  text-align: center;
}

/* Colors for Statuses (add more as needed) */
.status-open { background-color: var(--info); }
.status-in-progress, .status-in_progress { background-color: var(--primary); }
.status-completed { background-color: var(--success); }
.status-pending { background-color: var(--warning); color: var(--dark); }

/* Colors for Priorities */
.priority-high { background-color: var(--danger); }
.priority-medium { background-color: var(--warning); color: var(--dark); }
.priority-low { background-color: var(--secondary); }

/* Action Links in Table */
td .action-link {
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
  padding: 5px 8px;
  border-radius: 5px;
  transition: all 0.2s ease;
  margin: 0 4px;
}
td .action-link:hover {
  background-color: var(--primary);
  color: var(--white);
}

/* -- Responsive Card-based Layout -- */
@media (max-width: 768px) {
  .page-actions {
      flex-direction: column;
      align-items: stretch;
      gap: 1rem;
  }
    .add-task-btn, .back-link {
        text-align: center;
    }
    
  .table-container {
    background-color: transparent;
    box-shadow: none;
  }
  
  table, thead, tbody, th, td, tr {
    display: block;
  }
  
  thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }
  
  tr {
    margin-bottom: 1.5rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    border: 1px solid #e2e8f0;
    padding: 10px;
  }
  
  td {
    border: none;
    border-bottom: 1px solid #e8edf2;
    position: relative;
    padding-right: 50%; /* Space for label */
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Aligns content to the left in RTL */
  }

  td:last-child {
    border-bottom: none;
  }

  td:before {
    content: attr(data-label);
    font-weight: 700;
    color: #334155;
    position: absolute;
    top: 50%;
    right: 15px; /* Position label to the right */
    transform: translateY(-50%);
    text-align: right;
  }
}
  </style>
