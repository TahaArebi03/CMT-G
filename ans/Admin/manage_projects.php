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

// Define the ProjectManager class
class ProjectManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->conn;
    }

    // حذف مشروع
    public function deleteProject($projectId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM projects WHERE project_id = ?");
            $stmt->execute([$projectId]);
            return true;
        } catch (PDOException $e) {
            return "خطأ في حذف المشروع: " . $e->getMessage();
        }
    }

    // تعديل مشروع
    public function updateProject($updateData) {
        try {
            $stmt = $this->conn->prepare("UPDATE projects SET title=?, description=?, objectives=?, deadline=?, status=? WHERE project_id=?");
            $stmt->execute([
                $updateData['title'], $updateData['description'], $updateData['objectives'], $updateData['deadline'], $updateData['status'], $updateData['update_id']
            ]);
            return true;
        } catch (PDOException $e) {
            return "خطأ في تعديل المشروع: " . $e->getMessage();
        }
    }

    // إضافة مشروع جديد
    public function addProject($newProjectData) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $newProjectData['new_title'], $newProjectData['new_description'], $newProjectData['new_objectives'], $newProjectData['new_deadline'], $newProjectData['new_status'], $newProjectData['created_by']
            ]);
            return true;
        } catch (PDOException $e) {
            return "خطأ في إضافة المشروع: " . $e->getMessage();
        }
    }

    // جلب المشاريع
    public function getProjects() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM projects");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "خطأ في جلب المشاريع: " . $e->getMessage();
        }
    }
}

$connection = new Connect();
$projectManager = new ProjectManager($connection);

// حذف مشروع
if (isset($_GET['delete'])) {
    $message = $projectManager->deleteProject($_GET['delete']);
    if ($message === true) {
        header("Location: manage_projects.php");
        exit;
    } else {
        echo $message;
    }
}

// تعديل مشروع
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $message = $projectManager->updateProject([
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'objectives' => $_POST['objectives'],
        'deadline' => $_POST['deadline'],
        'status' => $_POST['status'],
        'update_id' => $_POST['update_id']
    ]);
    if ($message === true) {
        header("Location: manage_projects.php");
        exit;
    } else {
        echo $message;
    }
}

// إضافة مشروع جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_new'])) {
    $message = $projectManager->addProject([
        'new_title' => $_POST['new_title'],
        'new_description' => $_POST['new_description'],
        'new_objectives' => $_POST['new_objectives'],
        'new_deadline' => $_POST['new_deadline'],
        'new_status' => $_POST['new_status'],
        'created_by' => $_SESSION['user_id']
    ]);
    if ($message === true) {
        header("Location: manage_projects.php");
        exit;
    } else {
        echo $message;
    }
}

$projects = $projectManager->getProjects();
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
                <td>
                    <input type="hidden" name="update_id" value="<?= $proj['project_id'] ?>">
                    <button type="submit">💾 تحديث</button>
                </td>
            </form>
            <td>
                <a href="?delete=<?= $proj['project_id'] ?>" onclick="return confirm('⚠️ لا يمكن التراجع. هل أنت متأكد من الحذف؟')">🗑 حذف</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">لا توجد مشاريع حالياً.</td></tr>
    <?php endif; ?>
</table>
