<link rel="stylesheet" href="manage_projects.css">

<?php
// 🔧 ملف: Admin/manage_projects.php
// إدارة وإنشاء وتعديل المشاريع
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق') {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once '../Config/connect.php';

$connection = new Connect();
$conn = $connection->conn;

// حذف مشروع
if (isset($_GET['delete'])) {
    try {
        $delete_id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM projects WHERE project_id = ?");
        $stmt->execute([$delete_id]);
        header("Location: manage_projects.php");
        exit;
    } catch (PDOException $e) {
        echo "خطأ في حذف المشروع: " . $e->getMessage();
    }
}

// تعديل مشروع
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    try {
        $update_id = $_POST['update_id'];
        $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, objectives=?, deadline=?, status=? WHERE project_id=?");
        $stmt->execute([
            $_POST['title'], $_POST['description'], $_POST['objectives'], $_POST['deadline'], $_POST['status'], $update_id
        ]);
        header("Location: manage_projects.php");
        exit;
    } catch (PDOException $e) {
        echo "خطأ في تعديل المشروع: " . $e->getMessage();
    }
}

// إضافة مشروع جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_new'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['new_title'], $_POST['new_description'], $_POST['new_objectives'], $_POST['new_deadline'], $_POST['new_status'], $_SESSION['user_id']
        ]);
        header("Location: manage_projects.php");
        exit;
    } catch (PDOException $e) {
        echo "خطأ في إضافة المشروع: " . $e->getMessage();
    }
}

// جلب المشاريع
try {
    $stmt = $conn->prepare("SELECT * FROM projects");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "خطأ في جلب المشاريع: " . $e->getMessage();
}
?>

<h2>إدارة المشاريع</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">ادارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الإشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>

<form method="post">
    <h3>➕ إضافة مشروع جديد</h3>
    <input type="hidden" name="create_new" value="1">
    <label>الاسم: <input type="text" name="new_title" required></label>
    <label>الوصف: <input type="text" name="new_description"></label>
    <label>الأهداف: <input type="text" name="new_objectives"></label>
    <label>الموعد النهائي: <input type="date" name="new_deadline"></label>
    <label>الحالة:
        <select name="new_status">
            <option value="نشط">نشط</option>
            <option value="مؤرشف">مؤرشف</option>
        </select>
    </label>
    <button type="submit">➕ إنشاء</button>
</form>

<table border="1">
    <tr>
        <th>الاسم</th><th>الوصف</th><th>الأهداف</th><th>الموعد النهائي</th><th>الحالة</th><th>إجراءات</th>
    </tr>
    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $proj): ?>
        <tr>
            <form method="post">
                <td><input type="text" name="title" value="<?= htmlspecialchars($proj['title']) ?>"></td>
                <td><input type="text" name="description" value="<?= htmlspecialchars($proj['description']) ?>"></td>
                <td><input type="text" name="objectives" value="<?= htmlspecialchars($proj['objectives']) ?>"></td>
                <td><input type="date" name="deadline" value="<?= $proj['deadline'] ?>"></td>
                <td>
                    <select name="status">
                        <option value="نشط" <?= $proj['status'] === 'نشط' ? 'selected' : '' ?>>نشط</option>
                        <option value="مؤرشف" <?= $proj['status'] === 'مؤرشف' ? 'selected' : '' ?>>مؤرشف</option>
                    </select>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">لا توجد مشاريع حالياً.</td></tr>
    <?php endif; ?>
</table>
