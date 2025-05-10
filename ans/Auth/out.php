<?php
session_start();

// حذف جميع بيانات الجلسة
session_unset();
session_destroy();

// إعادة التوجيه لصفحة تسجيل الدخول
header("Location: inout.php");
exit;
?>
