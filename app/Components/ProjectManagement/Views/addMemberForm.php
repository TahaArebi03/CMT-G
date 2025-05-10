<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة عضو إلى المشروع</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../../public/css/addMemberForm.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    /* File: /app/Components/ProjectManagement/Assets/css/addMemberForm.css */

/* المتغيرات العامة */
:root {
    --primary: #4361ee;
    --primary-dark: #3a56d4;
    --secondary: #3f37c9;
    --light: #f8f9fa;
    --dark: #2b2d42;
    --gray: #6c757d;
    --white: #ffffff;
    --border-radius: 8px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
  }
  
  /* إعادة الضبط الأساسية */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Tajawal', sans-serif;
  }
  
  body {
    background-color: #f5f7ff;
    color: var(--dark);
    line-height: 1.6;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    direction: rtl;
  }
  
  /* حاوية النموذج */
  .form-container {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 30px;
    width: 100%;
    max-width: 500px;
    border-top: 4px solid var(--primary);
  }
  
  .form-container h2 {
    color: var(--primary);
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.5rem;
  }
  
  /* مجموعات النموذج */
  .form-group {
    margin-bottom: 25px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
  }
  
  .form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 1rem;
    background-color: var(--light);
    transition: var(--transition);
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: left 15px center;
    background-size: 15px;
  }
  
  .form-group select:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
  }
  
  /* زر الإرسال */
  .btn-submit {
    width: 100%;
    padding: 14px;
    background-color: var(--primary);
    color: var(--white);
    border: none;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 15px;
  }
  
  .btn-submit:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
  }
  
  .btn-submit:active {
    transform: translateY(0);
  }
  
  /* التصميم المتجاوب */
  @media (max-width: 768px) {
    .form-container {
      padding: 25px 20px;
      margin: 0 15px;
    }
    
    .form-container h2 {
      font-size: 1.3rem;
    }
  }
  
  /* تأثيرات الحركة */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .form-container {
    animation: fadeIn 0.5s ease-out;
  }
  </style>
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