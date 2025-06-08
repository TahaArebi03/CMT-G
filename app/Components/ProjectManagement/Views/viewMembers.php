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

</head>
<body>

<h3>أعضاء المشروع</h3>
<a href="ProjectController.php?action=view&id=<?= $project_id ?>" class="back-link">العودة إلى تفاصيل المشروع</a>
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
                      <?php if($user->getRole() === 'Admin'): ?>
                        <a href="../../ProjectManagement/Controllers/ProjectMemberController.php?
                        action=edit&project_id=<?= $project_id ?>&user_id=<?= $m->getUserId() ?>">تعديل</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>لا يوجد أعضاء في المشروع حاليًا.</p>
<?php endif; ?>

  </tbody>
</table>
<?php if ($user->getRole() === 'Admin'): ?>
<a href="../Controllers/ProjectMemberController.php?action=add&project_id=<?= $project_id ?>" class="btn">+ إضافة عضو</a>
<?php endif; ?>

</body>
</html>

<style>
:root {
  --primary: #4361ee;
  --primary-light: #e6e9ff;
  --primary-dark: #3a56d4;
  --success: #198754;
  --light: #f8f9fa;
  --dark: #2b2d42;
  --gray: #6c757d;
  --white: #ffffff;
  --border-radius: 8px;
  --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  --transition: all 0.3s ease;
}

/* إعادة الضبط الأساسية واستخدام الخط */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Tajawal', sans-serif;
  background-color: #f5f7ff;
  color: var(--dark);
  line-height: 1.6;
  direction: rtl;
}

.container {
  padding: 20px 30px;
  max-width: 1000px;
  margin: 20px auto;
}

/* عنوان الصفحة */
h3 {
  color: var(--primary);
  font-size: 2rem;
  margin-bottom: 20px;
  text-align: center;
}

/* رابط العودة */
.back-link {
  display: inline-block;
  margin-bottom: 25px;
  color: var(--gray);
  text-decoration: none;
  font-weight: 500;
  transition: var(--transition);
}

.back-link:hover {
  color: var(--primary);
  transform: translateX(3px); /* حركة بسيطة لليمين في وضع RTL */
}

/* حاوية الجدول لتحسين التحكم */
.table-container {
  width: 100%;
  background-color: var(--white);
  box-shadow: var(--box-shadow);
  border-radius: var(--border-radius);
  overflow: hidden; /* ضروري لعمل border-radius مع الجدول */
  margin-bottom: 30px;
}

/* الجدول */
table {
  width: 100%;
  border-collapse: collapse;
}

table thead {
  background-color: var(--primary);
  color: var(--white);
}

table th {
  padding: 15px;
  text-align: right;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

table td {
  padding: 15px;
  border-bottom: 1px solid #eef0f2;
}

table tbody tr:last-child td {
  border-bottom: none; /* إزالة الخط السفلي لآخر صف */
}

table tbody tr:hover {
  background-color: var(--primary-light);
}

/* رابط الإجراءات داخل الجدول */
.action-link {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 5px;
  background-color: var(--primary-light);
  color: var(--primary-dark);
  text-decoration: none;
  font-weight: 500;
  border: 1px solid transparent;
}

.action-link:hover {
  background-color: var(--primary);
  color: var(--white);
  text-decoration: none;
}

/* رسالة عدم وجود أعضاء */
.no-members {
  text-align: center;
  padding: 40px 20px;
  color: var(--gray);
  font-size: 1.1rem;
}

/* زر الإضافة */
.btn {
  display: inline-block;
  padding: 12px 25px;
  background-image: linear-gradient(45deg, var(--primary), var(--secondary));
  color: var(--white);
  border-radius: var(--border-radius);
  text-decoration: none;
  font-weight: 600;
  transition: var(--transition);
  border: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 7px 20px rgba(0, 0, 0, 0.25);
}


/* -- التصميم المتجاوب للشاشات الصغيرة -- */
@media (max-width: 768px) {
  .table-container {
    box-shadow: none;
    background-color: transparent;
  }
  
  table, thead, tbody, th, td, tr {
    display: block;
  }

  thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px; /* إخفاء رأس الجدول الأصلي */
  }

  tr {
    margin-bottom: 1rem;
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    border: 1px solid #eef0f2;
  }
  
  td {
    border: none;
    border-bottom: 1px solid #eef0f2;
    position: relative;
    padding-right: 50%; /* مساحة لإظهار عنوان الحقل */
    text-align: left;
    padding-top: 10px;
    padding-bottom: 10px;
  }

  td:last-child {
      border-bottom: none;
  }

  td:before {
    position: absolute;
    top: 50%;
    right: 15px; /* يمين بسبب RTL */
    width: 45%;
    padding-left: 10px;
    white-space: nowrap;
    content: attr(data-label); /* جلب النص من سمة data-label */
    font-weight: 700;
    color: var(--primary);
    transform: translateY(-50%);
    text-align: right;
  }

  .btn {
    width: 100%;
    text-align: center;
  }
}
</style>