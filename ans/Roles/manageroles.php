<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'مسؤول') {
    header("Location: ../Auth/inout.php");
    exit;
}

require_once "../Config/connect.php";
$db = new Connect();
$conn = $db->conn;

// تحديث الدور
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
    $stmt->execute([$new_role, $user_id]);
}

// جلب المستخدمين (ما عدا نفسك)
$stmt = $conn->prepare("SELECT user_id, name, email, role FROM users WHERE user_id != ?");
$stmt->execute([$_SESSION['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "../Includes/header.php"; ?>

<h2>إدارة الأدوار والصلاحيات</h2>

<table>
    <tr>
        <th>الاسم</th>
        <th>البريد الإلكتروني</th>
        <th>الدور الحالي</th>
        <th>تغيير الدور</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <select name="role">
                        <option value="طالب" <?= $user['role'] == 'طالب' ? 'selected' : '' ?>>طالب</option>
                        <option value="مسؤول" <?= $user['role'] == 'مسؤول' ? 'selected' : '' ?>>مسؤول</option>
                    </select>
                    <input type="submit" name="update_role" value="تحديث">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</div></body></html>
