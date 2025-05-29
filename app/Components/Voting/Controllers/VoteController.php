<?php
require_once __DIR__ . '/../Models/Vote.php';
require_once __DIR__ . '/../Models/VoteResponse.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';

class VoteController
{
    public function listAction()
    {
        $project_id = intval($_GET['project_id'] ?? 0);
        $voteModel = new Vote();
        $votes = $voteModel->getAllVotesByProject($project_id);

        include __DIR__ . '/../Views/voteList.php';
    }

    public function createAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vote = new Vote();
            $vote->setProjectId($_POST['project_id']);
            $vote->setQuestion($_POST['question']);
            $vote->setOptions($_POST['options']);
            $vote->setStatus($_POST['status']);
            $vote->setCreatedBy($_SESSION['user_id'] ?? 1); // مؤقتًا نستخدم 1

            if ($vote->createVote()) {
                header('Location: VoteController.php?action=list&project_id=' . $_POST['project_id']);
                exit;
            } else {
                echo "فشل في إنشاء التصويت";
            }
        } else {
            $project_id = $_GET['project_id'] ?? 0;
            include __DIR__ . '/../Views/voteForm.php';
        }
    }

    public function voteAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = new VoteResponse();

            $voteId = $_POST['vote_id'];
            $userId = $_SESSION['user_id'] ?? 1; // مؤقتًا
            $option = $_POST['selected_option'];

            if ($response->hasUserVoted($voteId, $userId)) {
                echo "لقد قمت بالتصويت مسبقًا";
                exit;
            }

            $response->setVoteId($voteId);
            $response->setUserId($userId);
            $response->setSelectedOption($option);

            if ($response->submitResponse()) {
                header('Location: VoteController.php?action=result&vote_id=' . $voteId);
                exit;
            } else {
                echo "فشل في إرسال التصويت";
            }
        }
    }

    public function resultAction()
    {
        $vote_id = $_GET['vote_id'] ?? 0;

        $vote = new Vote();
        $voteData = $vote->getVoteById($vote_id);

        $response = new VoteResponse();
        $results = $response->getResultsByVoteId($vote_id);

        include __DIR__ . '/../Views/voteResult.php';
    }
}

$controller = new VoteController();
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        $controller->createAction();
        break;
    case 'vote':
        $controller->voteAction();
        break;
    case 'result':
        $controller->resultAction();
        break;
    case 'list':
    default:
        $controller->listAction();
        break;
}
