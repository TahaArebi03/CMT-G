<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة عضو إلى المشروع</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../../public/css/addMemberForm.css">
</head>
<body>
  <div class="form-container">
    <h2>إضافة عضو جديد إلى المشروع</h2>
    <form method="post" action="../Controllers/ProjectMemberController.php?action=save&project_id=<?= $project_id ?>">
  <div class="form-group">
    <label for="user_id">اختر طالب:</label>
    <select name="user_id" id="user_id" required>
        <?php foreach ($students as $s): ?>
          <option value="<?= $s->getUserId() ?>">
            <?= htmlspecialchars($s->getName()) ?> - <?= htmlspecialchars($s->getMajor()) ?>
          </option>
        <?php endforeach; ?>
    </select>
  </div>
  
  <div class="form-group">
    <label for="role_in_project">الدور:</label>
    <select name="role_in_project" id="role_in_project" required>
      <option value="member">عضو</option>
      <option value="admin">قائد فريق</option>
    </select>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn-submit">
      حفظ العضو
    </button>
  </div>
</form>

  </div>
</body>
</html>