<link rel="stylesheet" href="manage_notifications.css">


<?php
session_start();
require_once "../config/connect.php";
$db = new Connect();
$conn = $db->conn;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'طالب') {
    header("Location: ../Auth/inout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "❌ خطأ في جلب الإشعارات: " . $e->getMessage();
    exit;
}
?>

<h2>📬 إشعاراتي</h2>
<?php include '../Includes/header.php'; ?>
<?php if (!empty($notifications)): ?>
    <?php foreach ($notifications as $notif): ?>
        <div>
            <p>🔔 <?= htmlspecialchars($notif['message']) ?></p>
            <small>📅 <?= $notif['created_at'] ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>🔕 لا توجد إشعارات حالياً.</p>
<?php endif; ?>
