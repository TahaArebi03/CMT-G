<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  ?>

  <meta charset="UTF-8">
  <title>Project Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../../public/css/projectForm.css">
</head>
<body>
  <div class="pf-container">
    <h1>🛠 Create / Edit Project</h1>
    <form action="../Controllers/ProjectController.php?action=create" method="post" class="pf-form">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" required>

      <label for="description">Description</label>
      <textarea id="description" name="description" rows="4" required></textarea>

      <label for="objectives">Objectives</label>
      <textarea id="objectives" name="objectives" rows="3"></textarea>

      <label for="deadline">Deadline</label>
      <input type="datetime-local" id="deadline" name="deadline" required>

      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="active">Active</option>
        <option value="archived">Archived</option>
      </select>

      <button type="submit">Create</button>
    </form>
  </div>
</body>
</html>
