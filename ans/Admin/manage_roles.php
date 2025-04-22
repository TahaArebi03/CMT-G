<link rel="stylesheet" href="manage_roles.css">

<?php
// 🔧 ملف: Admin/manage_roles.php
// إدارة الأدوار والصلاحيات بشكل مستقل
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول') {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once '../Config/connect.php';

$connection = new Connect();
$conn = $connection->conn;

// تعديل دور مستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    try {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$_POST['new_role'], $_POST['user_id']]);
        header("Location: manage_roles.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في تعديل الدور: " . $e->getMessage() . "</p>";
    }
}

// حذف مستخدم بعد التأكد من عدم وجود بيانات مرتبطة به
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    try {
        // تحقق من وجود تعليقات أو مهام مرتبطة بالمستخدم
        $check_dependencies = $conn->prepare("SELECT COUNT(*) FROM comments WHERE user_id = ?");
        $check_dependencies->execute([$user_id]);

        if ($check_dependencies->fetchColumn() > 0) {
            echo "<p style='color:red;'>⚠️ لا يمكن حذف هذا المستخدم لوجود بيانات مرتبطة به (مثل التعليقات).</p>";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            header("Location: manage_roles.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في حذف المستخدم: " . $e->getMessage() . "</p>";
    }
}

// إضافة مستخدم جديد مع التحقق من البريد المكرر
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        // تحقق هل البريد موجود مسبقاً
        $check = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn() == 0) {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $role]);
            header("Location: manage_roles.php");
            exit;
        } else {
            echo "<p style='color:red;'>⚠️ البريد الإلكتروني مستخدم مسبقاً.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إضافة المستخدم: " . $e->getMessage() . "</p>";
    }
}

// جلب المستخدمين
try {
    $stmt = $conn->prepare("SELECT user_id, name, email, role FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب المستخدمين: " . $e->getMessage() . "</p>";
    $users = [];
}
?>

<h2>إدارة الأدوار والصلاحيات</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
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
