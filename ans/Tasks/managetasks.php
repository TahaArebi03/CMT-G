<link rel="stylesheet" href="student_tasks.css">

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once "../Config/connect.php";
$db = new Connect();
$conn = $db->conn;

// جلب المشاريع اللي المستخدم عضو فيها
try {
    $stmt = $conn->prepare("
        SELECT p.project_id, p.title 
        FROM projects p 
        JOIN project_members pm ON p.project_id = pm.project_id 
        WHERE pm.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب المشاريع: " . $e->getMessage() . "</p>";
    $projects = [];
}

// إضافة مهمة جديدة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_task'])) {
    try {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $project_id = $_POST['project_id'];
        $assigned_to = $_POST['assigned_to'];
        $priority = $_POST['priority'];
        $deadline = $_POST['deadline'];

        $stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline) VALUES (?, ?, ?, ?, 'لم تبدأ', ?, ?)");
        $stmt->execute([$project_id, $title, $desc, $assigned_to, $priority, $deadline]);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إضافة المهمة: " . $e->getMessage() . "</p>";
    }
}

// تحديث حالة مهمة
if (isset($_POST['update_status'])) {
    try {
        $task_id = $_POST['task_id'];
        $new_status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
        $stmt->execute([$new_status, $task_id]);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في تحديث حالة المهمة: " . $e->getMessage() . "</p>";
    }
}

// جلب المهام اللي مخصصة للمستخدم
try {
    $stmt = $conn->prepare("
        SELECT t.*, p.title AS project_title 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.project_id 
        WHERE t.assigned_to = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $my_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب المهام: " . $e->getMessage() . "</p>";
    $my_tasks = [];
}

// فقط المسؤول يمكنه تعيين المهام
$members = [];
if ($_SESSION['user_role'] == 'مسؤول') {
    try {
        $stmt = $conn->query("SELECT user_id, name FROM users WHERE role = 'طالب'");
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في جلب المستخدمين: " . $e->getMessage() . "</p>";
        $members = [];
    }
}
?>

<?php include "../Includes/header.php"; ?>

<h2>إدارة المهام</h2>

<?php if ($_SESSION['user_role'] == 'مسؤول'): ?>
<form method="POST">
    <h3>إضافة مهمة جديدة</h3>

    <label>العنوان:</label>
    <input type="text" name="title" required>

    <label>الوصف:</label>
    <textarea name="description" required></textarea>

    <label>المشروع:</label>
    <select name="project_id" required>
        <?php foreach ($projects as $p): ?>
            <option value="<?= $p['project_id'] ?>"><?= htmlspecialchars($p['title']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>تعيين إلى:</label>
    <select name="assigned_to" required>
        <?php foreach ($members as $m): ?>
            <option value="<?= $m['user_id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>الأولوية:</label>
    <select name="priority">
        <option value="عالية">عالية</option>
        <option value="متوسطة">متوسطة</option>
        <option value="منخفضة">منخفضة</option>
    </select>

    <label>الموعد النهائي:</label>
    <input type="date" name="deadline" required>

    <input type="submit" name="create_task" value="إضافة المهمة">
</form>
<?php endif; ?>

<hr>

<h3>مهامي</h3>
<table>
    <tr>
        <th>المشروع</th>
        <th>العنوان</th>
        <th>الحالة</th>
        <th>الأولوية</th>
        <th>الموعد النهائي</th>
        <th>تحديث</th>
    </tr>
    <?php foreach ($my_tasks as $task): ?>
        <tr>
            <td><?= htmlspecialchars($task['project_title']) ?></td>
            <td><?= htmlspecialchars($task['title']) ?></td>
            <td><?= htmlspecialchars($task['status']) ?></td>
            <td><?= htmlspecialchars($task['priority']) ?></td>
            <td><?= htmlspecialchars($task['deadline']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                    <select name="status">
                        <option value="قيد التنفيذ">قيد التنفيذ</option>
                        <option value="مكتملة">مكتملة</option>
                    </select>
                    <input type="submit" name="update_status" value="تحديث">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div></body></html>
