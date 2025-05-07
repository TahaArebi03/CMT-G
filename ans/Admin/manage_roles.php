<link rel="stylesheet" href="manage_roles.css">

<?php
// 🔧 ملف: Admin/manage_roles.php
// إدارة الأدوار والصلاحيات بشكل مستقل
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق') {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once '../Config/connect.php';

// Define the UserManager class
class UserManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->conn;
    }

    // تعديل دور مستخدم
    public function changeRole($newRole, $userId) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
            $stmt->execute([$newRole, $userId]);
            return true;
        } catch (PDOException $e) {
            return "<p style='color:red;'>خطأ في تعديل الدور: " . $e->getMessage() . "</p>";
        }
    }

    // حذف مستخدم بعد التأكد من عدم وجود بيانات مرتبطة به
    public function deleteUser($userId) {
        try {
            $check_dependencies = $this->conn->prepare("SELECT COUNT(*) FROM comments WHERE user_id = ?");
            $check_dependencies->execute([$userId]);

            if ($check_dependencies->fetchColumn() > 0) {
                return "<p style='color:red;'>⚠️ لا يمكن حذف هذا المستخدم لوجود بيانات مرتبطة به (مثل التعليقات).</p>";
            } else {
                $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->execute([$userId]);
                return true;
            }
        } catch (PDOException $e) {
            return "<p style='color:red;'>خطأ في حذف المستخدم: " . $e->getMessage() . "</p>";
        }
    }

    // إضافة مستخدم جديد مع التحقق من البريد المكرر
    public function addUser($name, $email, $password, $role) {
        try {
            // تحقق هل البريد موجود مسبقاً
            $check = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetchColumn() == 0) {
                $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $password, $role]);
                return true;
            } else {
                return "<p style='color:red;'>⚠️ البريد الإلكتروني مستخدم مسبقاً.</p>";
            }
        } catch (PDOException $e) {
            return "<p style='color:red;'>خطأ في إضافة المستخدم: " . $e->getMessage() . "</p>";
        }
    }

    // جلب المستخدمين
    public function getUsers() {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, name, email, role FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "<p style='color:red;'>خطأ في جلب المستخدمين: " . $e->getMessage() . "</p>";
        }
    }
}

$connection = new Connect();
$userManager = new UserManager($connection);

// تعديل دور مستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    $message = $userManager->changeRole($_POST['new_role'], $_POST['user_id']);
    if ($message === true) {
        header("Location: manage_roles.php");
        exit;
    } else {
        echo $message;
    }
}

// حذف مستخدم
if (isset($_GET['delete_user'])) {
    $message = $userManager->deleteUser($_GET['delete_user']);
    if ($message === true) {
        header("Location: manage_roles.php");
        exit;
    } else {
        echo $message;
    }
}

// إضافة مستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $message = $userManager->addUser($name, $email, $password, $role);
    if ($message === true) {
        header("Location: manage_roles.php");
        exit;
    } else {
        echo $message;
    }
}

$users = $userManager->getUsers();
?>

<h2>إدارة الأدوار والصلاحيات</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">ادارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الإشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>

<h3>➕ إضافة مستخدم جديد</h3>
<form method="post">
    <input type="hidden" name="add_user" value="1">
    <label>الاسم: <input type="text" name="name" required></label>
    <label>البريد الإلكتروني: <input type="email" name="email" required></label>
    <label>كلمة المرور: <input type="password" name="password" required></label>
    <label>الدور:
        <select name="role">
            <option value="طالب">طالب</option>
            <option value="مسؤول">مسؤول</option>
            <option value="قائد فريق">قائد فريق</option>
        </select>
    </label>
    <button type="submit">➕ إضافة</button>
</form>
<br>

<table border="1">
    <tr>
        <th>الاسم</th><th>البريد الإلكتروني</th><th>الدور الحالي</th><th>تغيير الدور</th><th>حذف المستخدم</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <form method="post">
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <select name="new_role">
                    <option value="طالب" <?= $user['role'] === 'طالب' ? 'selected' : '' ?>>طالب</option>
                    <option value="مسؤول" <?= $user['role'] === 'مسؤول' ? 'selected' : '' ?>>مسؤول</option>
                    <option value="قائد فريق" <?= $user['role'] === 'قائد فريق' ? 'selected' : '' ?>>قائد فريق</option>
                </select>
                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                <button type="submit" name="change_role">💾 تحديث</button>
            </td>
            <td>
                <a href="?delete_user=<?= $user['user_id'] ?>" onclick="return confirm('⚠️ لا يمكن التراجع. هل أنت متأكد من الحذف؟')">🗑 حذف</a>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>
