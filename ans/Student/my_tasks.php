<link rel="stylesheet" href="student_tasks.css">

<?php
session_start();
require_once '../Config/connect.php';
$db = new Connect();
$conn = $db->conn;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'طالب') {
    header("Location: ../Auth/inout.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// إرسال تعليق
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO comments (task_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['task_id'], $user_id, $_POST['content']]);
        echo "<p style='color:green;'>✅ تم إرسال التعليق بنجاح</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ حدث خطأ: " . $e->getMessage() . "</p>";
    }
}

// جلب المهام الخاصة بالطالب
try {
    $stmt = $conn->prepare("SELECT tasks.*, projects.title AS project_title FROM tasks
                            JOIN projects ON tasks.project_id = projects.project_id
                            WHERE assigned_to = ?");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ فشل في جلب المهام: " . $e->getMessage() . "</p>";
    $tasks = [];
}

// جلب التعليقات وتخزينها في مصفوفة حسب task_id
$comments_map = [];
try {
    $stmt = $conn->query("SELECT comments.*, users.name FROM comments JOIN users ON users.user_id = comments.user_id ORDER BY created_at ASC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $comments_map[$row['task_id']][] = $row;
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ فشل في جلب التعليقات: " . $e->getMessage() . "</p>";
}
?>

<h2>📋 مهامي</h2>
<?php include "../Includes/header.php"; ?>
<?php foreach ($tasks as $task): ?>
    <div class="task-card">
        <h3>📌 <?= htmlspecialchars($task['title']) ?> (<?= htmlspecialchars($task['project_title']) ?>)</h3>
        <p>📝 <?= htmlspecialchars($task['description']) ?></p>
        <p>⏰ الموعد النهائي: <?= htmlspecialchars($task['deadline']) ?></p>
        <p>📊 الحالة: <?= $task['status'] ?> | 🎯 الأولوية: <?= $task['priority'] ?></p>

        <?php if ($task['allow_comments']): ?>
            <div class="comments-section">
                <h4>💬 التعليقات:</h4>
                <?php
                $comments = $comments_map[$task['task_id']] ?? [];
                if ($comments):
                    foreach ($comments as $comment):
                ?>
                    <div class="comment">
                        <strong><?= htmlspecialchars($comment['name']) ?>:</strong>
                        <?= nl2br(htmlspecialchars($comment['content'])) ?>
                        <br><small>📅 <?= $comment['created_at'] ?></small>
                    </div>
                <?php
                    endforeach;
                else:
                    echo "<p>لا توجد تعليقات بعد.</p>";
                endif;
                ?>

                <form method="POST" class="comment-form">
                    <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                    <textarea name="content" placeholder="✍️ اكتب تعليقك هنا..." required></textarea>
                    <button type="submit" name="add_comment">📩 إرسال</button>
                </form>
            </div>
        <?php else: ?>
            <p class="no-comments">💡 التعليقات غير مفعلة لهذه المهمة.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
