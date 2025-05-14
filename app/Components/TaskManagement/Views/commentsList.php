<?php


?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التعليقات</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<h3>التعليقات:</h3>
<ul>
    <?php if (empty($comments)): ?>
        <li>لا توجد تعليقات بعد.</li>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <li>
                <!-- user name -->
                <!-- <strong><?= htmlspecialchars($user_name) ?>:</strong> -->
                <strong><?= htmlspecialchars($comment->getUserId()) ?>:</strong>
                <?= htmlspecialchars($comment->getContent()) ?>
                <br>
                <small>تاريخ: <?= htmlspecialchars($comment->getCreatedAt()) ?></small>
                <br>
                <a href="../Controllers/CommentController.php?action=edit&comment_id=<?= $comment->getCommentId() ?>
                &task_id=<?= $task_id ?>&user_id=<?= $user_id ?>">تعديل</a>
                |
                <a href="../Controllers/CommentController.php?action=delete&comment_id=<?= $comment->getCommentId() ?>
                &task_id=<?= $task_id ?>&user_id=<?= $user_id ?>">حذف</a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<a href="../Controllers/CommentController.php?action=create&task_id=<?= $task_id ?>&user_id=<?= $user_id ?>" class="btn add">
    + إضافة تعليق
</a>

</body>
</html>
<style>
    /* General Body Styles (from previous CSS, ensure it's present) */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 20px;
    direction: rtl; /* Right-to-left for Arabic */
}

/* Heading Styles (from previous CSS, ensure it's present) */
h2, h3 { /* Added h3 here */
    color: #0056b3; /* Dark blue */
    margin-bottom: 20px;
}

h3 {
    text-align: right; /* Align comments heading to the right */
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}

/* Form Container Styles (from previous CSS, if needed on this page, otherwise optional here) */
form {
    background-color: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 20px auto; /* Center the form */
}

/* Label Styles (from previous CSS) */
label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

/* Textarea Styles (from previous CSS) */
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
}

textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
}

/* Button Styles (from previous CSS) */
button[type="submit"], .btn { /* Added .btn class for general button styling */
    background-color: #28a745; /* Green */
    color: white !important; /* Important to override link color if .btn is an <a> tag */
    padding: 10px 18px; /* Adjusted padding slightly for general use */
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    text-decoration: none; /* Remove underline from <a> tags styled as buttons */
    display: inline-block; /* Allows padding and margin for <a> tags */
    text-align: center;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover, .btn:hover {
    background-color: #218838; /* Darker green on hover */
}

/* NEW STYLES FOR COMMENTS DISPLAY */

/* Comments List Container */
ul {
    list-style-type: none; /* Remove default bullet points */
    padding: 0;
    margin-top: 20px;
    max-width: 700px; /* Or your preferred width */
    margin-left: auto;
    margin-right: auto;
}

/* Individual Comment Item */
ul li {
    background-color: #fff;
    border: 1px solid #e7e7e7;
    border-radius: 6px;
    padding: 15px 20px;
    margin-bottom: 15px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.05);
}

ul li:last-child {
    margin-bottom: 0;
}

/* User ID in Comment */
ul li strong {
    color: #0056b3; /* Same as heading color for consistency */
    font-size: 1.1em;
}

/* Comment Content */
ul li p { /* Assuming comment content might be wrapped in <p> tags in the future, or just for spacing */
    margin-top: 5px;
    margin-bottom: 10px;
    line-height: 1.7;
    color: #444;
}

/* Date in Comment */
ul li small {
    display: block; /* Make it take its own line */
    color: #777;
    font-size: 0.85em;
    margin-top: 8px;
    margin-bottom: 12px;
}

/* Edit and Delete Links */
ul li a {
    color: #007bff; /* Standard link blue */
    text-decoration: none;
    font-size: 0.9em;
    margin-left: 8px; /* Add some space between links if they are next to each other */
}
ul li a:first-of-type { /* For RTL, the first link would be "تعديل" */
    margin-left: 0;
    margin-right: 8px;
}


ul li a:hover {
    text-decoration: underline;
    color: #0056b3;
}

/* Specific style for delete link for visual cue, optional */
ul li a[href*="action=delete"] {
    color: #dc3545; /* Red color for delete */
}

ul li a[href*="action=delete"]:hover {
    color: #c82333; /* Darker red on hover */
}

/* "Add Comment" Button Link */
.btn.add { /* Specific styles for the add button if needed, inherits .btn */
    margin-top: 25px;
    display: block; /* Make it a block to take full width or to center it */
    width: fit-content; /* Adjust width to content */
    margin-left: auto;
    margin-right: auto; /* Center the button */
}


/* Responsive adjustments (from previous CSS, ensure it's present and adjust if needed) */
@media (max-width: 768px) {
    form, ul { /* Apply responsive padding to comments list as well */
        padding-left: 15px;
        padding-right: 15px;
    }

    h2, h3 {
        font-size: 1.4rem; /* Slightly smaller headings on mobile */
    }

    button[type="submit"], .btn {
        padding: 10px 15px;
        font-size: 0.9rem;
    }

    ul li {
        padding: 12px 15px;
    }
}
</style>