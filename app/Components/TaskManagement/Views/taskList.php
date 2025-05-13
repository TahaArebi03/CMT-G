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
 <style>
    /* General Body Styles */
body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  background-color: #f4f7f6;
  color: #333;
  margin: 0;
  padding: 20px;
  direction: rtl; /* Setting text direction for Arabic */
}

/* Main Heading */
h1 {
  color: #2c3e50;
  text-align: center;
  margin-bottom: 25px;
}

/* "Add Task" Button */
.btn.add {
  display: inline-block;
  background-color: #28a745; /* Green for add actions */
  color: white;
  padding: 10px 20px;
  margin-bottom: 25px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.btn.add:hover {
  background-color: #218838; /* Darker green on hover */
}

/* "No tasks" Message */
p {
  text-align: center;
  font-size: 1.1em;
  color: #777;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
  margin: 0 auto;
  background-color: #ffffff;
  border-radius: 8px;
  overflow: hidden; /* Ensures border-radius is respected by child elements like thead */
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Table Header */
thead tr {
  background-color: #34495e; /* Dark blue-gray for header */
  color: #ffffff;
  text-align: right; /* Align header text to the right for RTL */
}

th, td {
  padding: 12px 15px;
  border-bottom: 1px solid #ecf0f1; /* Light border for rows */
}

th {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.9em;
  letter-spacing: 0.5px;
}

/* Table Body */
tbody tr {
  transition: background-color 0.2s ease;
}

tbody tr:nth-of-type(even) {
  background-color: #f9f9f9; /* Subtle striping for even rows */
}

tbody tr:hover {
  background-color: #e8f4fd; /* Light blue hover for rows */
}

tbody td {
  color: #555;
}

/* Action Links in Table */
td a {
  color: #3498db; /* Blue for action links */
  text-decoration: none;
  font-weight: 500;
  padding: 5px 8px;
  border-radius: 4px;
  transition: background-color 0.2s ease, color 0.2s ease;
}

td a:hover {
  color: #ffffff;
  background-color: #2980b9; /* Darker blue background on hover */
  text-decoration: none;
}

/* Responsive adjustments (optional, but good practice) */
@media (max-width: 768px) {
  body {
    padding: 10px;
  }

  h1 {
    font-size: 1.8em;
  }

  .btn.add {
    display: block; /* Make button full width on smaller screens */
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    max-width: 250px; /* Optional: constrain width */
  }

  /*
    For very small screens, you might consider transforming the table
    into a card-based layout, but that's more complex and
    often requires JavaScript or more intricate CSS.
    The following is a simple scroll for overflow if table is too wide.
  */
  table {
    display: block;
    overflow-x: auto; /* Allows horizontal scrolling for the table if it's too wide */
    white-space: nowrap; /* Prevents text wrapping in cells that might break layout */
  }

  th, td {
    white-space: nowrap; /* Ensure cells don't wrap and break layout when scrolling */
  }
}
/* Additional styles can go here */
  </style>
