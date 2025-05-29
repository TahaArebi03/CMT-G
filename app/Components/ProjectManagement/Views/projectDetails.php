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
<style>
  /* General Body Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
  color: #333;
  direction: rtl; /* For Arabic language support */
}

/* Tabs Navigation */
.tabs {
  background-color: #333;
  overflow: hidden;
  border-bottom: 3px solid #555;
  padding: 0 20px;
}

.tabs a {
  float: right; /* Adjust for RTL */
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  transition: background-color 0.3s;
}

.tabs a:hover {
  background-color: #555;
  color: white;
}

.tabs a.active { /* If you add an active class to the current tab */
  background-color: #007bff;
  color: white;
}


/* Project Details Container */
.pd-container {
  background-color: #fff;
  margin: 20px auto;
  padding: 25px;
  border-radius: 8px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  max-width: 800px; /* Adjust as needed */
}

.pd-container h1 {
  color: #333;
  margin-bottom: 20px;
  font-size: 2em;
  border-bottom: 2px solid #eee;
  padding-bottom: 10px;
}

/* Project Info Section */
.pd-info p {
  margin-bottom: 15px;
  font-size: 1.1em;
  line-height: 1.8;
}

.pd-info p strong {
  color: #555;
  min-width: 120px; /* For alignment */
  display: inline-block;
}

/* Status Styling */
.status {
  padding: 5px 10px;
  border-radius: 5px;
  color: #fff;
  font-weight: bold;
  text-transform: capitalize;
}

.status.active {
  background-color: #28a745; /* Green for active */
}

.status.archived {
  background-color: #6c757d; /* Gray for archived */
}

/* Buttons Styling */
.btn {
  display: inline-block;
  padding: 10px 20px;
  font-size: 1em;
  font-weight: bold;
  text-align: center;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s, color 0.3s;
  cursor: pointer;
  border: none;
}

.btn.edit {
  background-color: #007bff; /* Blue for edit */
  color: #fff;
  margin-top: 20px;
}

.btn.edit:hover {
  background-color: #0056b3;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .tabs a {
    float: none;
    display: block;
    text-align: left; /* Adjust for RTL if needed */
  }
  .pd-container {
    margin: 10px;
    padding: 15px;
  }
  .pd-container h1 {
    font-size: 1.8em;
  }
  .pd-info p {
    font-size: 1em;
  }
  .pd-info p strong {
    display: block; /* Stack strong text on smaller screens */
    margin-bottom: 5px;
  }
}
</style>
  