<!-- ربط ملف التنسيق الخاص بالصفحة -->
<link rel="stylesheet" href="manage_projects.css">

<?php
// 🌐 بدء الجلسة لحفظ معلومات المستخدم
session_start();
require_once '../Config/connect.php'; // 📦 استدعاء ملف الاتصال بقاعدة البيانات

// ✅ (Refactoring 2) دالة تتحقق من أن المستخدم مسؤول أو قائد فريق
function isAuthorizedAdmin() {
    return isset($_SESSION['user_id']) &&
           ($_SESSION['role'] === 'مسؤول' || $_SESSION['role'] === 'قائد فريق');
}

// 🚫 إعادة توجيه المستخدم إذا لم يكن مخولًا
if (!isAuthorizedAdmin()) {
    header("Location: ../Auth/inout.php");
    exit;
}

// ⚙️ إنشاء الاتصال بقاعدة البيانات
$connection = new Connect();
$conn = $connection->conn;

/////////////////////////////////////////////////////////
// ✅ (Refactoring 1) فصل عمليات CRUD في دوال مستقلة //
/////////////////////////////////////////////////////////

// 🗑️ دالة لحذف مشروع
function deleteProject($conn, $projectId) {
    $stmt = $conn->prepare("DELETE FROM projects WHERE project_id = ?");
    $stmt->execute([$projectId]);
}

// 📝 دالة لتحديث مشروع
function updateProject($conn, $data) {
    $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, objectives=?, deadline=?, status=? WHERE project_id=?");
    $stmt->execute([
        $data['title'], $data['description'], $data['objectives'],
        $data['deadline'], $data['status'], $data['update_id']
    ]);
}

// ➕ دالة لإضافة مشروع جديد
function createProject($conn, $data, $creatorId) {
    $stmt = $conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['new_title'], $data['new_description'], $data['new_objectives'],
        $data['new_deadline'], $data['new_status'], $creatorId
    ]);
}

// 📦 تنفيذ حذف المشروع إذا تم الضغط على رابط الحذف
if (isset($_GET['delete'])) {
    try {
        deleteProject($conn, $_GET['delete']);
        header("Location: manage_projects.php");
        exit;
    } catch (PDOException $e) {
        echo "خطأ في حذف المشروع: " . $e->getMessage();
    }
}

// 🔄 تنفيذ تعديل المشروع إذا تم إرسال النموذج الخاص بالتعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    try {
        updateProject($conn, $_POST);
        header("Location: manage_projects.php");
        exit;
    } catch (PDOException $e) {
        echo "خطأ في تعديل المشروع: " . $e->getMessage();
    }
}

// 🆕 تنفيذ إضافة مشروع جديد إذا تم إرسال النموذج الخاص بالإضافة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_new'])) {
    try {
        createProject($conn, $_POST, $_SESSION['user_id']);
        header("Location: manage_projects.php");
        exit;
    } catch (PDOException $e) {
        echo "خطأ في إضافة المشروع: " . $e->getMessage();
    }
}

// 📥 جلب كل المشاريع من قاعدة البيانات لعرضها
try {
    $stmt = $conn->prepare("SELECT * FROM projects");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "خطأ في جلب المشاريع: " . $e->getMessage();
}
?>

<!-- 🧾 عنوان الصفحة -->
<h2>إدارة المشاريع</h2>

<!-- 🔗 قائمة التنقل بين صفحات الإدارة -->
<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_votes.php">ادارة التصويتات</a></li>
    <li><a href="manage_notifications.php">الإشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>

<!-- 🆕 نموذج إضافة مشروع جديد -->
<form method="post">
    <h3>➕ إضافة مشروع جديد</h3>
    <input type="hidden" name="create_new" value="1">

    <label>الاسم: <input type="text" name="new_title" required></label>
    <label>الوصف: <input type="text" name="new_description"></label>
    <label>الأهداف: <input type="text" name="new_objectives"></label>
    <label>الموعد النهائي: <input type="date" name="new_deadline"></label>

    <label>الحالة:
        <select name="new_status">
            <option value="نشط">نشط</option>
            <option value="مؤرشف">مؤرشف</option>
        </select>
    </label>

    <button type="submit">➕ إنشاء</button>
</form>

<!-- 📊 جدول عرض المشاريع الموجودة -->
<table border="1">
    <tr>
        <th>الاسم</th><th>الوصف</th><th>الأهداف</th><th>الموعد النهائي</th><th>الحالة</th><th>إجراءات</th>
    </tr>

    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $proj): ?>
        <tr>
            <!-- 📝 نموذج تعديل مشروع -->
            <form method="post">
                <td><input type="text" name="title" value="<?= htmlspecialchars($proj['title']) ?>"></td>
                <td><input type="text" name="description" value="<?= htmlspecialchars($proj['description']) ?>"></td>
                <td><input type="text" name="objectives" value="<?= htmlspecialchars($proj['objectives']) ?>"></td>
                <td><input type="date" name="deadline" value="<?= $proj['deadline'] ?>"></td>
                <td>
                    <select name="status">
                        <option value="نشط" <?= $proj['status'] === 'نشط' ? 'selected' : '' ?>>نشط</option>
                        <option value="مؤرشف" <?= $proj['status'] === 'مؤرشف' ? 'selected' : '' ?>>مؤرشف</option>
                    </select>
                </td>
                <td>
                    <!-- 🔄 زر تعديل / 🗑️ رابط حذف -->
                    <input type="hidden" name="update_id" value="<?= $proj['project_id'] ?>">
                    <button type="submit">💾 تحديث</button>
                    <a href="?delete=<?= $proj['project_id'] ?>" onclick="return confirm('هل تريد حذف المشروع؟')">🗑️ حذف</a>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">لا توجد مشاريع حالياً.</td></tr>
    <?php endif; ?>
</table>
