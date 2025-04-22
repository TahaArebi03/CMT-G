<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Auth/inout.php");
    exit;
}
?>

<?php include "../Includes/header.php"; ?>

<h2>مرحبًا بك في لوحة التحكم</h2>
<p>اختر من القائمة أعلاه لإدارة المشاريع، المهام، أو الأدوار.</p>

</div></body></html>
