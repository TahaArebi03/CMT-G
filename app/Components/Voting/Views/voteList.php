<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة التصويتات</title>
    <link rel="stylesheet" href="../../../../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>🗳️ قائمة التصويتات</h2>

        <a href="../Controllers/VoteController.php?action=create&project_id=<?= htmlspecialchars($_GET['project_id'] ?? 0) ?>">
            ➕ إنشاء تصويت جديد
        </a>

        <?php if (!empty($votes)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>السؤال</th>
                        <th>الحالة</th>
                        <th>إجراء</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($votes as $vote): ?>
                        <tr>
                            <td><?= htmlspecialchars($vote['question']) ?></td>
                            <td><?= htmlspecialchars($vote['status']) ?></td>
                            
                            <td>
                                <?php if ($vote['status'] === 'open'): ?>
                                    <!-- نعرض الخيارات المتاحة للتصويت -->
                                    <form action="VoteController.php?action=vote" method="POST" style="display:inline;">
                                        <input type="hidden" name="vote_id" value="<?= $vote['vote_id'] ?>">
                                        <select name="selected_option">
                                            <?php foreach ($options as $option): ?>
                                                <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit">تصويت</button>
                                    </form>
                                <?php else: ?>
                                    مغلق
                                <?php endif; ?>
                                |
                                <a href="../Controllers/VoteController.php?action=result&vote_id=<?= $vote['vote_id'] ?>">عرض النتائج</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>لا توجد تصويتات لهذا المشروع.</p>
        <?php endif; ?>

    </div>
</body>
</html>