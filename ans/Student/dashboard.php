<?php
// 🔒 ملف: Student/dashboard.php
// صفحة لوحة تحكم الطالب
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'طالب') {
    header("Location: ../Auth/inout.php");
    exit;
}
?>

<h2>مرحباً بك في لوحة تحكم الطالب</h2>
<ul>
    <li><a href="my_tasks.php">📋 مهامي</a></li>
    <li><a href="vote.php">🗳️ التصويت</a></li>
    <li><a href="notifications.php">الإشعارات</a></li> 
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
