<link rel="stylesheet" href="manage_notifications.css">

<?php
session_start();
require_once "../config/connect.php";
$db = new Connect();
$conn = $db->conn;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق') {
    header("Location: ../Auth/inout.php");
    exit;
}

// جلبب الطلاب
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
    $message = trim($_POST['message']);
    $target = $_POST['target'];

    try {
        if ($target === 'all') {
            foreach ($students as $student) {
                $insert = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
                $insert->execute([$student['user_id'], $message]);
            }
        } else {
            $insert = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $insert->execute([$target, $message]);
        }

        echo "✅ تم إرسال الإشعار بنجاح.";
    } catch (PDOException $e) {
        echo "❌ فشل في إرسال الإشعار: " . $e->getMessage();
    }
}
?>

<h2>📢 إرسال إشعار للطلاب</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">ادارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الاشعارات</a></li>
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
