<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة تعليق جديد</title>
    <link rel="stylesheet" href="/path/to/your/css/styles.css"> <!-- ضع مسار الـ CSS الخاص بك هنا -->
</head>
<body>

<h2> إضافة تعليق جديد للمهمة  <?= htmlspecialchars($task->getTitle()) ?></h2>

<form action="../Controllers/CommentController.php?action=create&task_id=<?=$task_id ?>
&user_id=<?=$user_id?>&project_id=<?=$project_id?>" method="POST">

    <div>
        <label for="content">نص التعليق:</label>
        <textarea id="content" name="content" rows="4" required></textarea>
    </div>

    <div>
        <button type="submit">إضافة التعليق</button>
    </div>
</form>

</body>
</html>
<style>
    /* General Body Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 20px;
    direction: rtl; /* Right-to-left for Arabic */
}

/* Heading Styles */
h2 {
    color: #0056b3; /* Dark blue */
    text-align: center;
    margin-bottom: 20px;
}

/* Form Container Styles */
form {
    background-color: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 20px auto; /* Center the form */
}

/* Form Division Styles */
form div {
    margin-bottom: 15px;
}

/* Label Styles */
label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

/* Textarea Styles */
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; /* So padding doesn't affect width */
    font-family: inherit; /* Use the same font as the body */
    font-size: 1rem;
    resize: vertical; /* Allow vertical resizing */
}

textarea:focus {
    border-color: #007bff; /* Blue border on focus */
    outline: none; /* Remove default browser outline */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
}

/* Button Styles */
button[type="submit"] {
    background-color: #28a745; /* Green */
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #218838; /* Darker green on hover */
}

/* Responsive adjustments (optional) */
@media (max-width: 768px) {
    form {
        padding: 20px;
    }

    h2 {
        font-size: 1.5rem;
    }

    button[type="submit"] {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}
</style>