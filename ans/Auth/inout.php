<?php
session_start();
require_once "../Config/connect.php";

$db = new Connect();
$conn = $db->conn;

// التحقق من تسجيل الدخول
$login_error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // دعم كلمة مرور مشفرة أو عادية
    if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        // التوجيه حسب الدور
        if ($user['role'] === 'طالب') {
            header("Location: ../Student/my_tasks.php");
        } else {
            header("Location: ../Admin/dashboard.php");
        }
        exit;
    } else {
        $login_error = "بيانات الدخول غير صحيحة!";
    }
}
?>

<?php include "../Includes/header.php"; ?>

<form method="POST" action="">
    <h2>تسجيل الدخول</h2>
    <?php if ($login_error): ?>
        <p style="color:red;"><?php echo $login_error; ?></p>
    <?php endif; ?>
    
    <label for="email">البريد الإلكتروني:</label>
    <input type="email" name="email" required>

    <label for="password">كلمة المرور:</label>
    <input type="password" name="password" required>

    <input type="submit" value="تسجيل الدخول">
</form>

</div></body></html>
