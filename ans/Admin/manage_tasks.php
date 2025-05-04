<link rel="stylesheet" href="manage_tasks.css">


<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق') {
    header("Location: ../Auth/inout.php");
    exit;
}

$role = $_SESSION['role'];
require_once '../Config/connect.php';

$connection = new Connect();
$conn = $connection->conn;

// حذف مهمة
if (isset($_GET['delete_task'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = ?");
        $stmt->execute([$_GET['delete_task']]);
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في حذف المهمة: " . $e->getMessage() . "</p>";
    }
}

// تعديل مهمة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    try {
        $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, assigned_to=?, status=?, priority=?, deadline=?, allow_comments=? WHERE task_id=?");
        $stmt->execute([
            $_POST['title'], $_POST['description'], $_POST['assigned_to'], $_POST['status'], $_POST['priority'], $_POST['deadline'], isset($_POST['allow_comments']) ? 1 : 0, $_POST['task_id']
        ]);
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في تعديل المهمة: " . $e->getMessage() . "</p>";
    }
}

// إرسال مهمة للجميع
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task_all'])) {
    try {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE role = 'طالب'");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($students as $student) {
            $insert = $conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline, allow_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->execute([
                $_POST['project_id'], $_POST['title'], $_POST['description'], $student['user_id'], $_POST['status'], $_POST['priority'], $_POST['deadline'], isset($_POST['allow_comments']) ? 1 : 0
            ]);
        }
        echo "<p style='color:green;'>✅ تم إرسال المهمة لجميع الطلاب بنجاح</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إرسال المهمة للطلاب: " . $e->getMessage() . "</p>";
    }
}

// إضافة مهمة لطالب محدد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline, allow_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['project_id'], $_POST['title'], $_POST['description'], $_POST['assigned_to'], $_POST['status'], $_POST['priority'], $_POST['deadline'], isset($_POST['allow_comments']) ? 1 : 0
        ]);
        echo "<p style='color:green;'>✅ تم إضافة المهمة بنجاح</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إضافة المهمة: " . $e->getMessage() . "</p>";
    }
}

// حذف تعليق (فقط للمسؤول)
if ($role === 'مسؤول' && isset($_GET['delete_comment'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
        $stmt->execute([$_GET['delete_comment']]);
        header("Location: manage_tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في حذف التعليق: " . $e->getMessage() . "</p>";
    }
}

// إضافة تعليق
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO comments (task_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['task_id'], $user_id, $_POST['content']]);
        echo "<p style='color:green;'>✅ تم إضافة التعليق بنجاح</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>خطأ في إضافة التعليق: " . $e->getMessage() . "</p>";
    }
}


// جلب المهام
try {
    $stmt = $conn->prepare("SELECT tasks.*, users.name AS assigned_name, projects.title AS project_title FROM tasks
                            LEFT JOIN users ON tasks.assigned_to = users.user_id
                            LEFT JOIN projects ON tasks.project_id = projects.project_id");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب المهام: " . $e->getMessage() . "</p>";
    $tasks = [];
}

// جلب المشاريع والمستخدمين
try {
    $projects = $conn->query("SELECT project_id, title FROM projects")->fetchAll(PDO::FETCH_ASSOC);
    $users = $conn->query("SELECT user_id, name FROM users")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب البيانات: " . $e->getMessage() . "</p>";
    $projects = [];
    $users = [];
}

// جلب التعليقات
$comments_map = [];
try {
    $stmt = $conn->query("SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id = u.user_id ORDER BY c.created_at DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $comments_map[$row['task_id']][] = $row;
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>خطأ في جلب التعليقات: " . $e->getMessage() . "</p>";
}
?>

<h2>➕ إضافة مهمة جديدة</h2>

<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">ادارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الإشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>

<form method="post">
    <input type="hidden" name="add_task" value="1">
    <label>المشروع:
        <select name="project_id">
            <?php foreach ($projects as $proj): ?>
                <option value="<?= $proj['project_id'] ?>"><?= htmlspecialchars($proj['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>العنوان: <input type="text" name="title" required></label>
    <label>الوصف: <input type="text" name="description"></label>
    <label>المسند إليه:
        <select name="assigned_to">
            <?php foreach ($users as $usr): ?>
                <option value="<?= $usr['user_id'] ?>"><?= htmlspecialchars($usr['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>الحالة:
        <select name="status">
            <option value="لم تبدأ">لم تبدأ</option>
            <option value="قيد التنفيذ">قيد التنفيذ</option>
            <option value="مكتملة">مكتملة</option>
            <option value="قيد المراجعة">قيد المراجعة</option>
        </select>
    </label>
    <label>الأولوية:
        <select name="priority">
            <option value="عالية">عالية</option>
            <option value="متوسطة">متوسطة</option>
            <option value="منخفضة">منخفضة</option>
        </select>
    </label>
    <label>الموعد النهائي: <input type="date" name="deadline"></label>
    <label>السماح بالتعليقات:
        <select name="allow_comments">
            <option value="1" selected>مسموح</option>
            <option value="0">غير مسموح</option>
        </select>
    </label>
    <button type="submit">➕ إضافة لطالب</button>
</form>

<h3>📢 إرسال مهمة لجميع الطلاب</h3>
<form method="post">
    <input type="hidden" name="add_task_all" value="1">
    <label>المشروع:
        <select name="project_id">
            <?php foreach ($projects as $proj): ?>
                <option value="<?= $proj['project_id'] ?>"><?= htmlspecialchars($proj['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>العنوان: <input type="text" name="title" required></label>
    <label>الوصف: <input type="text" name="description"></label>
    <label>الحالة:
        <select name="status">
            <option value="لم تبدأ">لم تبدأ</option>
            <option value="قيد التنفيذ">قيد التنفيذ</option>
            <option value="مكتملة">مكتملة</option>
            <option value="قيد المراجعة">قيد المراجعة</option>
        </select>
    </label>
    <label>الأولوية:
        <select name="priority">
            <option value="عالية">عالية</option>
            <option value="متوسطة">متوسطة</option>
            <option value="منخفضة">منخفضة</option>
        </select>
    </label>
    <label>الموعد النهائي: <input type="date" name="deadline"></label>
    <label>السماح بالتعليقات:
        <select name="allow_comments">
            <option value="1" selected>مسموح</option>
            <option value="0">غير مسموح</option>
        </select>
    </label>
    <button type="submit">📤 إرسال للجميع</button>
</form>

<h2>إدارة المهام</h2>
<table border="1">
    <tr>
        <th>المشروع</th><th>العنوان</th><th>الوصف</th><th>المسند إليه</th><th>الحالة</th><th>الأولوية</th><th>الموعد النهائي</th><th>تعليقات</th><th>إجراءات</th>
    </tr>
    <?php foreach ($tasks as $task): ?>
    <tr>
        <form method="post">
            <td><?= htmlspecialchars($task['project_title']) ?></td>
            <td><input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>"></td>
            <td><input type="text" name="description" value="<?= htmlspecialchars($task['description']) ?>"></td>
            <td>
                <select name="assigned_to">
                    <?php foreach ($users as $usr): ?>
                        <option value="<?= $usr['user_id'] ?>" <?= $usr['user_id'] == $task['assigned_to'] ? 'selected' : '' ?>><?= htmlspecialchars($usr['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="status">
                    <option value="لم تبدأ" <?= $task['status'] === 'لم تبدأ' ? 'selected' : '' ?>>لم تبدأ</option>
                    <option value="قيد التنفيذ" <?= $task['status'] === 'قيد التنفيذ' ? 'selected' : '' ?>>قيد التنفيذ</option>
                    <option value="مكتملة" <?= $task['status'] === 'مكتملة' ? 'selected' : '' ?>>مكتملة</option>
                    <option value="قيد المراجعة" <?= $task['status'] === 'قيد المراجعة' ? 'selected' : '' ?>>قيد المراجعة</option>
                </select>
            </td>
            <td>
                <select name="priority">
                    <option value="عالية" <?= $task['priority'] === 'عالية' ? 'selected' : '' ?>>عالية</option>
                    <option value="متوسطة" <?= $task['priority'] === 'متوسطة' ? 'selected' : '' ?>>متوسطة</option>
                    <option value="منخفضة" <?= $task['priority'] === 'منخفضة' ? 'selected' : '' ?>>منخفضة</option>
                </select>
            </td>
            <td><input type="date" name="deadline" value="<?= $task['deadline'] ?>"></td>
            <td>
                <select name="allow_comments">
                    <option value="1" <?= $task['allow_comments'] ? 'selected' : '' ?>>مسموح</option>
                    <option value="0" <?= !$task['allow_comments'] ? 'selected' : '' ?>>غير مسموح</option>
                </select>
            </td>
            <td>
                <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                <button type="submit" name="update_task">💾 حفظ</button>
                <a href="?delete_task=<?= $task['task_id'] ?>" onclick="return confirm('هل أنت متأكد من حذف المهمة؟')">🗑 حذف</a>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>

<h2>📋 المهام والتعليقات</h2>

<?php foreach ($tasks as $task): ?>
    <div class="task-box">
        <h3><?= htmlspecialchars($task['title']) ?> (<?= htmlspecialchars($task['project_title']) ?>)</h3>
        <p><?= htmlspecialchars($task['description']) ?></p>
        <p><strong>لـ:</strong> <?= htmlspecialchars($task['assigned_name']) ?> | 
           <strong>الحالة:</strong> <?= $task['status'] ?> | 
           <strong>الأولوية:</strong> <?= $task['priority'] ?> | 
           <strong>الموعد:</strong> <?= $task['deadline'] ?>
        </p>

        <?php if ($task['allow_comments']): ?>
            <div class="comments-section">
                <h4>💬 التعليقات:</h4>
                <?php if (isset($comments_map[$task['task_id']])): ?>
                    <?php foreach ($comments_map[$task['task_id']] as $comment): ?>
                        <div class="comment">
                            <p><strong><?= htmlspecialchars($comment['name']) ?>:</strong> <?= htmlspecialchars($comment['content']) ?></p>
                            <small><?= $comment['created_at'] ?></small>
                            <?php if ($role === 'مسؤول'): ?>
                                <a href="?delete_comment=<?= $comment['comment_id'] ?>" onclick="return confirm('هل تريد حذف هذا التعليق؟')">🗑 حذف</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>لا توجد تعليقات.</p>
                <?php endif; ?>

                <form method="post" class="comment-form">
                    <input type="hidden" name="add_comment" value="1">
                    <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                    <textarea name="content" placeholder="اكتب تعليقك..." required></textarea>
                    <button type="submit">➕ إضافة تعليق</button>
                </form>
            </div>
        <?php else: ?>
            <p><em>🔒 التعليقات غير مفعّلة لهذه المهمة.</em></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>