<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/ProjectMember.php';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>أعضاء المشروع</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  


  <style>
:root {
  --primary: #4361ee;
  --primary-light: #e6e9ff;
  --primary-dark: #3a56d4;
  --secondary: #3f37c9;
  --success: #4cc9f0;
  --danger: #f72585;
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
  padding: 20px;
  direction: rtl;
}

/* التبويبات (في حالة تفعيلها) */
.tabs {
  display: flex;
  justify-content: center;
  background-color: var(--white);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
  border-radius: var(--border-radius);
}

.tabs a {
  padding: 15px 25px;
  text-decoration: none;
  color: var(--gray);
  font-weight: 600;
  transition: var(--transition);
  border-bottom: 3px solid transparent;
}

.tabs a:hover {
  color: var(--primary);
}

/* عنوان الصفحة */
h3 {
  color: var(--primary);
  font-size: 1.5rem;
  margin-bottom: 20px;
  text-align: center;
  padding-bottom: 10px;
  border-bottom: 2px solid var(--primary-light);
}

/* الجدول */
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 30px;
  background-color: var(--white);
  box-shadow: var(--box-shadow);
  border-radius: var(--border-radius);
  overflow: hidden;
}

table thead {
  background-color: var(--primary);
  color: var(--white);
}

table th {
  padding: 15px;
  text-align: right;
  font-weight: 600;
}

table td {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
}

table tbody tr:hover {
  background-color: var(--primary-light);
}

/* رسالة عدم وجود أعضاء */
p {
  text-align: center;
  padding: 20px;
  background-color: var(--white);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  color: var(--gray);
  margin-bottom: 30px;
}

/* الروابط */
a {
  color: var(--primary);
  text-decoration: none;
  transition: var(--transition);
}

a:hover {
  text-decoration: underline;
}

table a {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 4px;
  background-color: var(--primary-light);
  color: var(--primary);
}

table a:hover {
  background-color: var(--primary);
  color: var(--white);
  text-decoration: none;
}

/* زر الإضافة */
.btn {
  display: inline-block;
  padding: 12px 25px;
  background-color: var(--primary);
  color: var(--white);
  border-radius: var(--border-radius);
  text-decoration: none;
  font-weight: 600;
  transition: var(--transition);
  margin-top: 10px;
}

.btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

/* التصميم المتجاوب */
@media (max-width: 768px) {
  table {
    display: block;
    overflow-x: auto;
  }
  
  .tabs {
    flex-direction: column;
  }
  
  .tabs a {
    border-bottom: 1px solid #eee;
  }
  
  table th, table td {
    padding: 10px;
  }
  
  .btn {
    width: 100%;
    text-align: center;
  }
}
  </style>



</head>
<body>

<!-- <div class="tabs">
  <a href="projectDetails.php?project_id=<?= $project_id ?>">تفاصيل المشروع</a>
  <a href="viewMembers.php?project_id=<?= $project_id ?>">الأعضاء</a>
  <a href="../../TaskManagement/Views/projectTask.php?project_id= <? $project_id ?>">المهام</a>
</div> -->

<h3>أعضاء المشروع</h3>
<table>
  <thead>
    <tr><th>الاسم</th><th>الدور</th><th>التخصص</th><th>إجراءات</th></tr>
  </thead>
  <tbody>
  <?php if (!empty($members)): ?>
    <table>
        <thead>
            <tr><th>الاسم</th><th>الدور</th><th>التخصص</th><th>إجراءات</th></tr>
        </thead>
        <tbody>
            <?php foreach ($members as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m->getName()) ?></td>
                    <td><?= htmlspecialchars($m->getRole()) ?></td>
                    <td><?= htmlspecialchars($m->getMajor()) ?></td>
                    <td>
                        <a href="../../ProjectManagement/Controllers/ProjectMemberController.php?
                        action=edit&project_id=<?= $project_id ?>&user_id=<?= $m->getUserId() ?>">تعديل</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>لا يوجد أعضاء في المشروع حاليًا.</p>
<?php endif; ?>

  </tbody>
</table>

<a href="../Controllers/ProjectMemberController.php?action=add&project_id=<?= $project_id ?>" class="btn">+ إضافة عضو</a>

</body>
</html>
