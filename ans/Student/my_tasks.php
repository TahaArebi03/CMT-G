<link rel="stylesheet" href="student_tasks.css">

<?php
// 🔧 ملف: Student/my_tasks.php
// عرض المهام المخصصة للمستخدم وتحديث حالتها فقط
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'طالب') {
    header("Location: ../Auth/inout.php");
    exit;
}

include '../Includes/header.php';
require_once '../Config/connect.php';

$connection = new Connect();
$conn = $connection->conn;
$user_id = $_SESSION['user_id'];

// تحديث حالة مهمة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    try {
        $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ? AND assigned_to = ?");
        $stmt->execute([$status, $task_id, $user_id]);
        header("Location: my_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>حدث خطأ أثناء تحديث حالة المهمة: " . $e->getMessage() . "</p>";
    }
}

// جلب المهام المسندة للمستخدم
try {
    $stmt = $conn->prepare("SELECT tasks.*, projects.title AS project_title FROM tasks
                            LEFT JOIN projects ON tasks.project_id = projects.project_id
                            WHERE tasks.assigned_to = ?");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>حدث خطأ أثناء جلب المهام: " . $e->getMessage() . "</p>";
    $tasks = [];
}
?>

<h2>📋 مهامي</h2>
<table border="1">
    <tr>
        <th>المشروع</th><th>العنوان</th><th>الوصف</th><th>الحالة</th><th>الأولوية</th><th>الموعد النهائي</th><th>إجراء</th>
    </tr>
    <?php foreach ($tasks as $task): ?>
    <tr>
        <form method="post">
            <td><?= htmlspecialchars($task['project_title']) ?></td>
            <td><?= htmlspecialchars($task['title']) ?></td>
            <td><?= htmlspecialchars($task['description']) ?></td>
            <td>
                <select name="status">
                    <option value="لم تبدأ" <?= $task['status'] === 'لم تبدأ' ? 'selected' : '' ?>>لم تبدأ</option>
                    <option value="قيد التنفيذ" <?= $task['status'] === 'قيد التنفيذ' ? 'selected' : '' ?>>قيد التنفيذ</option>
                    <option value="مكتملة" <?= $task['status'] === 'مكتملة' ? 'selected' : '' ?>>مكتملة</option>
                </select>
            </td>
            <td><?= htmlspecialchars($task['priority']) ?></td>
            <td><?= htmlspecialchars($task['deadline']) ?></td>
            <td>
                <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                <button type="submit" name="update_status">💾 حفظ الحالة</button>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>
