<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/ProjectMember.php';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>أعضاء المشروع</title>
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
