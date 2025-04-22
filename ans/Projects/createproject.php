<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'مسؤول') {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once "../Config/connect.php";
$db = new Connect();
$conn = $db->conn;

// إنشاء مشروع جديد
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $objectives = $_POST['objectives'];
    $deadline = $_POST['deadline'];
    $created_by = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, 'نشط', ?)");
        $stmt->execute([$title, $description, $objectives, $deadline, $created_by]);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إنشاء المشروع: " . $e->getMessage() . "</p>";
    }
}

// أرشفة مشروع
if (isset($_GET['archive'])) {
    $project_id = $_GET['archive'];
    try {
        $stmt = $conn->prepare("UPDATE projects SET status = 'مؤرشف' WHERE project_id = ?");
        $stmt->execute([$project_id]);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في أرشفة المشروع: " . $e->getMessage() . "</p>";
    }
}

// جلب المشاريع التي أنشأها المستخدم
try {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE created_by = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب المشاريع: " . $e->getMessage() . "</p>";
    $projects = [];
}
?>

<?php include "../Includes/header.php"; ?>

<h2>إدارة المشاريع</h2>

<form method="POST">
    <h3>إنشاء مشروع جديد</h3>
    <label>اسم المشروع:</label>
    <input type="text" name="title" required>

    <label>الوصف:</label>
    <textarea name="description" required></textarea>

    <label>الأهداف:</label>
    <textarea name="objectives" required></textarea>

    <label>الموعد النهائي:</label>
    <input type="date" name="deadline" required>

    <input type="submit" name="create" value="إنشاء المشروع">
</form>

<hr>

<h3>مشاريعي</h3>
<table>
    <tr>
        <th>الاسم</th>
        <th>الحالة</th>
        <th>الموعد النهائي</th>
        <th>الإجراء</th>
    </tr>
    <?php foreach ($projects as $project): ?>
        <tr>
            <td><?= htmlspecialchars($project['title']) ?></td>
            <td><?= $project['status'] ?></td>
            <td><?= $project['deadline'] ?></td>
            <td>
                <?php if ($project['status'] == 'نشط'): ?>
                    <a href="?archive=<?= $project['project_id'] ?>">أرشفة</a>
                <?php else: ?>
                    مؤرشف
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div></body></html>
