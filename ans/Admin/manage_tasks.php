<link rel="stylesheet" href="manage_tasks.css">

<?php
// 🔧 ملف: Admin/manage_tasks.php
// إدارة المهام: إنشاء، تعديل، حذف حسب الأولوية + إرسال للجميع
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول') {
    header("Location: ../Auth/inout.php");
    exit;
}
// Note line 34
require_once '../Config/connect.php';

$connection = new Connect();
$conn = $connection->conn;

// حذف مهمة
if (isset($_GET['delete_task'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
        $stmt->execute([$_GET['delete_task']]);
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في حذف المهمة: " . $e->getMessage() . "</p>";
    }
}

// تعديل مهمة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    try {
        $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, assigned_to=?, status=?, priority=?, deadline=? WHERE task_id=?");
        $stmt->execute([
            $_POST['title'], $_POST['description'], $_POST['assigned_to'], $_POST['status'], $_POST['priority'], $_POST['deadline'], $_POST['task_id']
        ]);
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في تعديل المهمة: " . $e->getMessage() . "</p>";
    }
}

// إرسال مهمة للجميع
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task_all'])) {
    try {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE role = 'طالب'");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($students as $student) {
            $insert = $conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->execute([
                $_POST['project_id'], $_POST['title'], $_POST['description'], $student['user_id'], $_POST['status'], $_POST['priority'], $_POST['deadline']
            ]);
        }
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إرسال المهمة للطلاب: " . $e->getMessage() . "</p>";
    }
}

// إضافة مهمة لطالب محدد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['project_id'], $_POST['title'], $_POST['description'], $_POST['assigned_to'], $_POST['status'], $_POST['priority'], $_POST['deadline']
        ]);
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إضافة المهمة: " . $e->getMessage() . "</p>";
    }
}

// جلب المهام
try {
    $stmt = $conn->prepare("SELECT tasks.*, users.name AS assigned_name, projects.title AS project_title FROM tasks
                            LEFT JOIN users ON tasks.assigned_to = users.user_id
                            LEFT JOIN projects ON tasks.project_id = projects.project_id");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب المهام: " . $e->getMessage() . "</p>";
    $tasks = [];
}

// جلب المشاريع والمستخدمين
try {
    $projects = $conn->query("SELECT project_id, title FROM projects")->fetchAll(PDO::FETCH_ASSOC);
    $users = $conn->query("SELECT user_id, name FROM users")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب البيانات: " . $e->getMessage() . "</p>";
    $projects = [];
    $users = [];
}
?>

<h2>➕ إضافة مهمة جديدة</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
</ul>

<form method="post">
    <input type="hidden" name="add_task" value="1">
    <label>المشروع:
        <select name="project_id">
            <?php foreach ($projects as $proj): ?>
                <option value="<?= $proj['project_id'] ?>"><?= htmlspecialchars($proj['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>العنوان: <input type="text" name="title" required></label>
    <label>الوصف: <input type="text" name="description"></label>
    <label>المسند إليه:
        <select name="assigned_to">
            <?php foreach ($users as $usr): ?>
                <option value="<?= $usr['user_id'] ?>"><?= htmlspecialchars($usr['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>الحالة:
        <select name="status">
            <option value="لم تبدأ">لم تبدأ</option>
            <option value="قيد التنفيذ">قيد التنفيذ</option>
            <option value="مكتملة">مكتملة</option>
            <option value="قيد المراجعة">قيد المراجعة</option>
        </select>
    </label>
    <label>الأولوية:
        <select name="priority">
            <option value="عالية">عالية</option>
            <option value="متوسطة">متوسطة</option>
            <option value="منخفضة">منخفضة</option>
        </select>
    </label>
    <label>الموعد النهائي: <input type="date" name="deadline"></label>
    <button type="submit">➕ إضافة لطالب</button>
</form>

<h3>📢 إرسال مهمة لجميع الطلاب</h3>
<form method="post">
    <input type="hidden" name="add_task_all" value="1">
    <label>المشروع:
        <select name="project_id">
            <?php foreach ($projects as $proj): ?>
                <option value="<?= $proj['project_id'] ?>"><?= htmlspecialchars($proj['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>العنوان: <input type="text" name="title" required></label>
    <label>الوصف: <input type="text" name="description"></label>
    <label>الحالة:
        <select name="status">
            <option value="لم تبدأ">لم تبدأ</option>
            <option value="قيد التنفيذ">قيد التنفيذ</option>
            <option value="مكتملة">مكتملة</option>
            <option value="قيد المراجعة">قيد المراجعة</option>
        </select>
    </label>
    <label>الأولوية:
        <select name="priority">
            <option value="عالية">عالية</option>
            <option value="متوسطة">متوسطة</option>
            <option value="منخفضة">منخفضة</option>
        </select>
    </label>
    <label>الموعد النهائي: <input type="date" name="deadline"></label>
    <button type="submit">📤 إرسال للجميع</button>
</form>

<h2>إدارة المهام</h2>
<table border="1">
    <tr>
        <th>المشروع</th><th>العنوان</th><th>الوصف</th><th>المسند إليه</th><th>الحالة</th><th>الأولوية</th><th>الموعد النهائي</th><th>إجراءات</th>
    </tr>
    <?php foreach ($tasks as $task): ?>
    <tr>
        <form method="post">
            <td><?= htmlspecialchars($task['project_title']) ?></td>
            <td><input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>"></td>
            <td><input type="text" name="description" value="<?= htmlspecialchars($task['description']) ?>"></td>
            <td>
                <select name="assigned_to">
                    <?php foreach ($users as $usr): ?>
                        <option value="<?= $usr['user_id'] ?>" <?= $usr['user_id'] == $task['assigned_to'] ? 'selected' : '' ?>><?= htmlspecialchars($usr['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="status">
                    <option value="لم تبدأ" <?= $task['status'] === 'لم تبدأ' ? 'selected' : '' ?>>لم تبدأ</option>
                    <option value="قيد التنفيذ" <?= $task['status'] === 'قيد التنفيذ' ? 'selected' : '' ?>>قيد التنفيذ</option>
                    <option value="مكتملة" <?= $task['status'] === 'مكتملة' ? 'selected' : '' ?>>مكتملة</option>
                    <option value="قيد المراجعة" <?= $task['status'] === 'قيد المراجعة' ? 'selected' : '' ?>>قيد المراجعة</option>
                </select>
            </td>
            <td>
                <select name="priority">
                    <option value="عالية" <?= $task['priority'] === 'عالية' ? 'selected' : '' ?>>عالية</option>
                    <option value="متوسطة" <?= $task['priority'] === 'متوسطة' ? 'selected' : '' ?>>متوسطة</option>
                    <option value="منخفضة" <?= $task['priority'] === 'منخفضة' ? 'selected' : '' ?>>منخفضة</option>
                </select>
            </td>
            <td><input type="date" name="deadline" value="<?= $task['deadline'] ?>"></td>
            <td>
                <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                <button type="submit" name="update_task">💾 حفظ</button>
                <a href="?delete_task=<?= $task['task_id'] ?>" onclick="return confirm('هل أنت متأكد من حذف المهمة؟')">🗑 حذف</a>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>
