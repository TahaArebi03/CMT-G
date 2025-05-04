<?php
// 🔒 ملف: Admin/dashboard.php
// صفحة لوحة التحكم الرئيسية للمسؤول
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'مسؤول' && $_SESSION['role'] !== 'قائد فريق') {
    header("Location: ../Auth/inout.php");
    exit;
}

?>
<h2>مرحبا بك في لوحة تحكم المسؤول</h2>
<ul>
    <li><a href="manage_projects.php">إدارة المشاريع</a></li>
    <li><a href="manage_tasks.php">إدارة المهام</a></li>
    <li><a href="manage_roles.php">إدارة الأدوار والصلاحيات</a></li>
    <li><a href="manage_notifications.php">الإشعارات</a></li>
    <li><a href="../Auth/out.php" onclick="return confirm('هل أنت متأكد أنك تريد تسجيل الخروج؟');">🔓 تسجيل الخروج</a></li>
</ul>






<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f5f7;
    color: #333;
    padding: 30px;
    direction: rtl;
    text-align: center;
}

h2 {
    color: #2c3e50;
    margin-bottom: 30px;
}

ul {
    list-style-type: none;
    padding: 0;
    max-width: 400px;
    margin: 0 auto;
}

ul li {
    background-color: #ffffff;
    margin-bottom: 15px;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    transition: background-color 0.3s;
}

ul li:hover {
    background-color: #e3f2fd;
}

ul li a {
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
}

ul li a:hover {
    text-decoration: underline;
}
</style>
