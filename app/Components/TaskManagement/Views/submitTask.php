<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';
// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسليم المهمة</title>
    <link rel="stylesheet" href="../../../../public/css/task_submission.css">
</head>
<body>
    <div class="submission-container">
        <div class="submission-header">
            <a href="../Controllers/TaskController.php?action=list&project_id=<?= $task->getProjectId() ?>" class="btn btn-back">
                <span class="icon">←</span> العودة إلى قائمة المهام
            </a>
            <h1 class="submission-title">
                <span class="icon">📤</span> تسليم المهمة: <?= htmlspecialchars($task->getTitle()) ?>
            </h1>
        </div>

        <div class="submission-card">
            <form action="../Controllers/TaskController.php?action=upload&task_id=<?= $task->getTaskId() ?>" method="POST" enctype="multipart/form-data" class="submission-form">
                <div class="form-group">
                    <label for="submission_file" class="form-label">اختر الملف المطلوب رفعه</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="submission_file" name="submission_file" required class="file-input">
                        <label for="submission_file" class="file-upload-label">
                            <span class="file-icon">📎</span>
                            <span class="file-text">انقر لاختيار الملف</span>
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                     <div class='form-actions'>
                         <button> 
                            <span class="icon">📨</span> رفع وتسليم المهمة
                         </button>
                     </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<style>
    /* Base RTL Styles */
body {
    font-family: 'Tahoma', 'Arial', sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
    color: #333;
    direction: rtl;
}

/* Container */
.submission-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
}

/* Header */
.submission-header {
    margin-bottom: 30px;
    border-bottom: 1px solid #eee;
    padding-bottom: 20px;
}

.submission-title {
    color: #2c3e50;
    font-size: 1.5rem;
    margin: 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Card */
.submission-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 30px;
}

/* Form Elements */
.submission-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.1rem;
}

/* File Upload */
.file-upload {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.file-input {
    display: none;
}

.file-label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background-color: #f8f9fa;
    border: 2px dashed #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-label:hover {
    background-color: #e9ecef;
    border-color: #3498db;
}

.file-icon {
    font-size: 1.2rem;
}

.file-text {
    font-weight: 600;
}

.file-name {
    color: #7f8c8d;
    font-size: 0.9rem;
    padding: 0 5px;
}

.file-instructions {
    color: #7f8c8d;
    font-size: 0.85rem;
    line-height: 1.6;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    gap: 8px;
}

.btn-back {
    background-color: transparent;
    color: #3498db;
    border: 1px solid #3498db;
    padding: 8px 16px;
}

.btn-back:hover {
    background-color: #e8f4fc;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-start;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

/* Icons */
.icon {
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .submission-container {
        padding: 15px;
    }
    
    .submission-card {
        padding: 20px;
    }
    
    .btn {
        width: 100%;
    }
}
</style>
