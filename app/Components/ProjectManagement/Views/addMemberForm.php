
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Project Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="">
</head>
<body>
    <form method="post" action="ProjectMemberController.php?action=add">
    <input type="hidden" name="project_id" value="<?= $projectId ?>">
    <label>اختر طالب:</label>
    <select name="user_id">
        <?php foreach (StudentUser::findAllStudents() as $u): ?>
        <option value="<?= $u->getUserId() ?>">
            <?= htmlspecialchars($u->getName()) ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>الدور:</label>
    <select name="role_in_project">
        <option value="member">عضو</option>
        <option value="admin">قائد فريق</option>
    </select>
    <button type="submit">حفظ</button>
    </form>
