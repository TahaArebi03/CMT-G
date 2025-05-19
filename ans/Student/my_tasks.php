<link rel="stylesheet" href="student_tasks.css">
<?php
session_start();
require_once '../Config/connect.php';
require_once 'TaskFacade.php';

$db = new Connect();
$conn = $db->conn;

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'طالب') {
    header("Location: ../Auth/inout.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$facade = new TaskFacade($conn, $user_id);

// ✅ تحديث حالة المهمة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    $facade->updateTaskStatus($task_id, $status);
}

// إرسال تعليق
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $facade->addComment($_POST['task_id'], $_POST['content']);
}

// البحث في المهام باستخدام Facade
$keyword = $_GET['keyword'] ?? '';
$before_date = $_GET['before_date'] ?? null;
$tasks = $facade->fetchTasks($keyword, $before_date);

// جلب التعليقات باستخدام Facade
$comments_map = $facade->fetchComments();
?>

<h2>📋 مهامي</h2>

<?php include "../Includes/header.php"; ?>

<!-- 🔍 نموذج البحث -->
<form method="GET" class="search-form">
    <input type="text" name="keyword" placeholder="🔎 ابحث في عنوان المهام..." value="<?= htmlspecialchars($keyword) ?>">
    <input type="date" name="before_date" value="<?= htmlspecialchars($before_date) ?>">
    <button type="submit">بحث</button>
</form>

<?php foreach ($tasks as $task): ?>
    <div class="task-card">
        <h3>📌 <?= htmlspecialchars($task['title']) ?> (<?= htmlspecialchars($task['project_title']) ?>)</h3>
        <p>📝 <?= htmlspecialchars($task['description']) ?></p>
        <p>⏰ الموعد النهائي: <?= htmlspecialchars($task['deadline']) ?></p>
        <p>📊 الحالة الحالية: <?= $task['status'] ?> | 🎯 الأولوية: <?= $task['priority'] ?></p>

        <!-- ✅ نموذج تغيير الحالة -->
        <form method="POST" class="status-form">
            <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
            <select name="status" required>
                <option value="">-- اختر الحالة --</option>
                <option value="قيد التنفيذ" <?= $task['status'] === 'قيد التنفيذ' ? 'selected' : '' ?>>قيد التنفيذ</option>
                <option value="مكتملة" <?= $task['status'] === 'مكتملة' ? 'selected' : '' ?>>مكتملة</option>
            </select>
            <button type="submit" name="update_status">🔄 تحديث الحالة</button>
        </form>

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
