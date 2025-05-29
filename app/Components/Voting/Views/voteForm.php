<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>إنشاء تصويت</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../../public/css/form.css">
</head>
<body>
  <div class="form-container">
    <h2>🗳️ إنشاء تصويت جديد</h2>
    <form  method="POST" action="../Controllers/VoteController.php?action=create&
    project_id=<?= htmlspecialchars($project_id) ?>">

      <label for="question">سؤال التصويت:</label>
      <input type="text" name="question" id="question" required>

      <label for="status">الحالة:</label>
      <select name="status" id="status">
        <option value="open">مفتوح</option>
        <option value="closed">مغلق</option>
      </select>

      <button type="submit">إنشاء التصويت</button>
    </form>
  </div>
</body>
</html>
