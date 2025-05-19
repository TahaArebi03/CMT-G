<link rel="stylesheet" href="manage_notifications.css">

<?php
session_start();
require_once "../config/connect.php";
require_once "Commands/SendNotification_Command.php";


$db = new Connect();
$conn = $db->conn;

// التحقق من الجلسة والصلاحيات
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق')) {
    header("Location: ../Auth/inout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// جلب الطلاب
try {
    $stmt = $conn->prepare("SELECT user_id, name FROM users WHERE role = 'طالب'");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "خطأ في جلب الطلاب: " . $e->getMessage();
    exit;
}

// إرسال الإشعار
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $target = $_POST['target'];

    try {
        $command = new SendNotification_Command($conn, $message);

        if ($target === 'all') {
            $command->sendToAll($students);
        } else {
            $command->sendToUser($target);
        }

        echo "<p style='color:green;'>✅ تم إرسال الإشعار بنجاح.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ فشل في إرسال الإشعار: " . $e->getMessage() . "</p>";
    }
}
?>

<h2>📢 إرسال إشعار للطلاب</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">إدارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الإشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>

<form method="POST">
    <textarea name="message" placeholder="📝 اكتب محتوى الإشعار هنا..." required></textarea>

    <label>📌 اختر المستلم:</label>
    <select name="target" required>
        <option value="all">📨 لجميع الطلاب</option>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['user_id'] ?>">👤 <?= htmlspecialchars($student['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">🔔 إرسال الإشعار</button>
</form>
