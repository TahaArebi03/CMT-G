<?php
require_once __DIR__ . '/../Models/VoteResponse.php';
require_once __DIR__ . '/../Models/Vote.php';

$voteId = $_GET['vote_id'];
$responseModel = new VoteResponse();
$results = $responseModel->getResults($voteId);

$voteModel = new Vote();
$vote = $voteModel->getVoteById($voteId);
$options = json_decode($vote['options'], true);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>نتائج التصويت</title>
    <link rel="stylesheet" href="../../../../public/css/results.css">
</head>
<body>
    <div class="results-container">
        <h2>نتائج التصويت</h2>
        <p><strong>السؤال:</strong> <?= htmlspecialchars($vote['question']) ?></p>
        <ul>
            <?php
            foreach ($options as $option) {
                $count = 0;
                foreach ($results as $res) {
                    if ($res['selected_option'] === $option) {
                        $count = $res['count'];
                        break;
                    }
                }
                echo "<li><strong>" . htmlspecialchars($option) . ":</strong> $count صوت</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
