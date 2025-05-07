<link rel="stylesheet" href="vote.css">

<?php
require_once "../config/connect.php";
$db = new Connect();
$conn = $db->conn;

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'طالب') {
    header("Location: ../Auth/inout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM votes WHERE status = 'مفتوح'");
    $stmt->execute();
    $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ فشل في تحميل التصويتات: " . $e->getMessage() . "</p>";
    $votes = [];
}

// تنفيذ التصوييت
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote_id']) && isset($_POST['option'])) {
    $vote_id = $_POST['vote_id'];
    $option = $_POST['option'];

    try {
        $check = $conn->prepare("SELECT * FROM vote_responses WHERE vote_id = ? AND user_id = ?");
        $check->execute([$vote_id, $user_id]);

        if ($check->rowCount() === 0) {
            $stmt = $conn->prepare("INSERT INTO vote_responses (vote_id, user_id, selected_option) VALUES (?, ?, ?)");
            $stmt->execute([$vote_id, $user_id, $option]);
            echo "<p style='color:green;'>✅ تم التصويت بنجاح</p>";
        } else {
            echo "<p style='color:orange;'>⚠️ لقد صوتت مسبقًا لهذا التصويت</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ خطأ في التصويت: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- HTML -->


<h2>🗳️ التصويتات المفتوحة</h2>
<?php include '../Includes/header.php'; ?>
<?php if (!empty($votes)): ?>
    <?php foreach ($votes as $vote): ?>
        <form method="POST" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($vote['question']) ?></strong><br>
            <?php
            $options = json_decode($vote['options']);
            foreach ($options as $opt): ?>
                <label>
                    <input type="radio" name="option" value="<?= htmlspecialchars($opt) ?>" required>
                    <?= htmlspecialchars($opt) ?>
                </label><br>
            <?php endforeach; ?>
            <input type="hidden" name="vote_id" value="<?= $vote['vote_id'] ?>">
            <button type="submit">صوّت</button>
        </form>
    <?php endforeach; ?>
<?php else: ?>
    <p>🔕 لا توجد تصويتات متاحة حالياً.</p>
<?php endif; ?>
