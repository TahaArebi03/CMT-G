<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'مسؤول') {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once "../Config/connect.php";

class ProjectManager {
    private $conn;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->conn = $db->conn;
        $this->user_id = $user_id;
    }

    // إنشاء مشروع جديد
    public function createProject($title, $description, $objectives, $deadline) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, 'نشط', ?)");
            $stmt->execute([$title, $description, $objectives, $deadline, $this->user_id]);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>خطأ في إنشاء المشروع: " . $e->getMessage() . "</p>";
        }
    }

    // أرشفة مشروع
    public function archiveProject($project_id) {
        try {
            $stmt = $this->conn->prepare("UPDATE projects SET status = 'مؤرشف' WHERE project_id = ?");
            $stmt->execute([$project_id]);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>خطأ في أرشفة المشروع: " . $e->getMessage() . "</p>";
        }
    }

    // جلب المشاريع التي أنشأها المستخدم
    public function getUserProjects() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM projects WHERE created_by = ?");
            $stmt->execute([$this->user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>خطأ في جلب المشاريع: " . $e->getMessage() . "</p>";
            return [];
        }
    }
}

$db = new Connect();
$projectManager = new ProjectManager($db, $_SESSION['user_id']);

// Handle POST request for creating a new project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $objectives = $_POST['objectives'];
    $deadline = $_POST['deadline'];
    $projectManager->createProject($title, $description, $objectives, $deadline);
}

// Handle GET request for archiving a project
if (isset($_GET['archive'])) {
    $project_id = $_GET['archive'];
    $projectManager->archiveProject($project_id);
}

// Fetch user projects
$projects = $projectManager->getUserProjects();
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
