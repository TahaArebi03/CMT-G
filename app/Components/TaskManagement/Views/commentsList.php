<h3>التعليقات:</h3>
<ul>
    <?php if (empty($comments)): ?>
        <li>لا توجد تعليقات بعد.</li>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <li>
                <?= htmlspecialchars($comment->content) ?>
                <small>(<?= $comment->created_at ?>)</small>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<form method="POST" action="../Controllers/CommentController.php?action=create&task_id=<?= $task_id ?>">
    <textarea name="content" required></textarea>
    <input type="hidden" name="user_id" value="<?= $user_id ?>">
    <button type="submit">إضافة تعليق</button>