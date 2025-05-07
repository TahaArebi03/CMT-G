<link rel="stylesheet" href="manage_votes.css">

<?php
require_once "../config/connect.php";
$db = new Connect();
$conn = $db->conn;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق') {
    echo "غير مصرح";
    exit;
}

// حذف التصوييت
if (isset($_GET['delete_vote'])) {
    $vote_id = $_GET['delete_vote'];
    try {
        $stmt = $conn->prepare("DELETE FROM vote_responses WHERE vote_id = ?");
        $stmt->execute([$vote_id]);

        $stmt2 = $conn->prepare("DELETE FROM votes WHERE vote_id = ?");
        $stmt2->execute([$vote_id]);

        echo "<p style='color:green;'>✅ تم حذف التصويت</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ خطأ في حذف التصويت: " . $e->getMessage() . "</p>";
    }
}

// إنشاء تصويت جديد
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_vote'])) {
    $project_id = 1; // مؤقتاً نربط كل التصويتات بمشروع رقم 1
    $question = $_POST['question'];
    $options = json_encode(explode("\n", trim($_POST['options'])), JSON_UNESCAPED_UNICODE); // ✅ تعديل هنا
    $created_by = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO votes (project_id, question, options, status, created_by) VALUES (?, ?, ?, 'مفتوح', ?)");
        $stmt->execute([$project_id, $question, $options, $created_by]);
        echo "<p style='color:green;'>✅ تم إنشاء التصويت بنجاح</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ خطأ في إنشاء التصويت: " . $e->getMessage() . "</p>";
    }
}

// جلب التصويتات
try {
    $stmt = $conn->prepare("SELECT * FROM votes ORDER BY vote_id DESC");
    $stmt->execute();
    $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ فشل في جلب التصويتات: " . $e->getMessage() . "</p>";
    $votes = [];
}

// حساب عدد الأصوات
function countVotes($conn, $vote_id, $option) {
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM vote_responses WHERE vote_id = ? AND TRIM(selected_option) = ?");
        $stmt->execute([$vote_id, $option]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return "خطأ";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة التصويتات</title>
    <link rel="stylesheet" href="manage_votes.css">
</head>
<body>

<h2>ادارة التصويتات</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">ادارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الاشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>

<h2>📝 إنشاء تصويت جديد <span>🗳️</span></h2>
<form method="POST">
    <input type="text" name="question" placeholder="اكتب السؤال هنا" required><br>
    <textarea name="options" placeholder="كل خيار في سطر" required></textarea><br>
    <button type="submit" name="create_vote">إنشاء التصويت</button>
</form>

<hr>

<h2>📊 نتائج التصويتات <span>📈</span></h2>
<?php if (!empty($votes)): ?>
    <?php foreach ($votes as $vote): ?>
    <div class="vote-box">
        <strong>📝 السؤال:</strong> <?= htmlspecialchars($vote['question']) ?><br>
        <strong>📌 الحالة:</strong> <?= htmlspecialchars($vote['status']) ?><br><br>
                <!-- ✅ زر الحذف منسق -->
        <a href="?delete_vote=<?= $vote['vote_id'] ?>" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف التصويت؟');">🗑️ حذف التصويت</a>
        <ul>
            <?php
            $options = json_decode($vote['options']);
            foreach ($options as $opt):
                $count = countVotes($conn, $vote['vote_id'], trim($opt));
                ?>
                <li><?= htmlspecialchars($opt) ?>: <strong><?= $count ?></strong> صوت</li>
            <?php endforeach; ?>
        </ul>
        <br>
    </div>
<?php endforeach; ?>

<?php else: ?>
    <p>🔕 لا توجد تصويتات حالياً.</p>
<?php endif; ?>

</body>
</html>
