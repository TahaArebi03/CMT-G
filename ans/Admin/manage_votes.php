<?php
require_once "../config/connect.php";
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق')) {
    echo "غير مصرح";
    exit;
}

class VoteManager {
    private $conn;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->conn = $db->conn;
        $this->user_id = $user_id;
    }

    // حذف التصويت
    public function deleteVote($vote_id) {
        try {
            $stmt1 = $this->conn->prepare("DELETE FROM vote_responses WHERE vote_id = ?");
            $stmt1->execute([$vote_id]);

            $stmt2 = $this->conn->prepare("DELETE FROM votes WHERE vote_id = ?");
            $stmt2->execute([$vote_id]);

            return "<p style='color:green;'>✅ تم حذف التصويت</p>";
        } catch (PDOException $e) {
            return "<p style='color:red;'>❌ خطأ في حذف التصويت: " . $e->getMessage() . "</p>";
        }
    }

    // إنشاء تصويت جديد
    public function createVote($question, $options) {
        $project_id = 1; // مؤقتاً نربط كل التصويتات بمشروع رقم 1
        $created_by = $this->user_id;
        $options_json = json_encode(explode("\n", trim($options)), JSON_UNESCAPED_UNICODE);

        try {
            $stmt = $this->conn->prepare("INSERT INTO votes (project_id, question, options, status, created_by) VALUES (?, ?, ?, 'مفتوح', ?)");
            $stmt->execute([$project_id, $question, $options_json, $created_by]);
            return "<p style='color:green;'>✅ تم إنشاء التصويت بنجاح</p>";
        } catch (PDOException $e) {
            return "<p style='color:red;'>❌ خطأ في إنشاء التصويت: " . $e->getMessage() . "</p>";
        }
    }

    // جلب التصويتات
    public function getVotes() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM votes ORDER BY vote_id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ فشل في جلب التصويتات: " . $e->getMessage() . "</p>";
            return [];
        }
    }

    // حساب عدد الأصوات
    public function countVotes($vote_id, $option) {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM vote_responses WHERE vote_id = ? AND TRIM(selected_option) = ?");
            $stmt->execute([$vote_id, $option]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return "خطأ";
        }
    }
}

$db = new Connect();
$voteManager = new VoteManager($db, $_SESSION['user_id']);

// Handle deletion of a vote
if (isset($_GET['delete_vote'])) {
    $vote_id = $_GET['delete_vote'];
    echo $voteManager->deleteVote($vote_id);
}

// Handle creation of a new vote
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_vote'])) {
    $question = $_POST['question'];
    $options = $_POST['options'];
    echo $voteManager->createVote($question, $options);
}

// Fetch all votes
$votes = $voteManager->getVotes();
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
        <!-- ✅ زر الحذف -->
        <a href="?delete_vote=<?= $vote['vote_id'] ?>" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف التصويت؟');">🗑️ حذف التصويت</a>
        <ul>
            <?php
            $options = json_decode($vote['options']);
            foreach ($options as $opt):
                $count = $voteManager->countVotes($vote['vote_id'], trim($opt));
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
