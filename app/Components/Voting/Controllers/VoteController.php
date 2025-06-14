<?php
require_once __DIR__ . '/../Models/Vote.php';
require_once __DIR__ . '/../Models/VoteResponse.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';


class VoteController
{
    public function __construct()
    {
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function listAction()
    {
        $project_id = intval($_GET['project_id'] ?? 0);
        $user_id = $_SESSION['user_id'] ?? 0;
        $user= User::findById($user_id);
        $votes = Vote::getAllVotesByProject($project_id);
        if (!empty($votes)) {
            foreach ($votes as $index => $vote) {
                $userVote = VoteResponse::getUserVoteResponse($vote['vote_id'], $user_id);
                $votes[$index]['selected_option'] = $userVote;  
            }
        }
        
        include __DIR__ . '/../Views/voteList.php';
    }

    public function createAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
            $vote = new Vote();
            $vote->setProjectId($_GET['project_id']);
            $vote->setQuestion($_POST['question']);
            $vote->setStatus($_POST['status']);
            $vote->setCreatedBy($_SESSION['user_id']); 

            if ($vote->createVote()) {
                
                header('Location: VoteController.php?action=list&project_id=' . $vote->getProjectId());
                exit;
            } else {
                throw new Exception("فشل في إنشاء التصويت");
            }
        } catch (Exception $e) {
            echo " خطأ اتناء انشاء التصويت: " . $e->getMessage();
        }
    } else {
        $project_id = $_GET['project_id'] ?? 0;
        include __DIR__ . '/../Views/voteForm.php';
      }
    }
    public function voteAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'vote_id' => $_GET['vote_id'] ?? 0,
                    'project_id' => $_GET['project_id'] ?? 0,
                    'user_id' => $_SESSION['user_id'] ?? 0,
                    'selected_option' => $_POST['selected_option'] ?? null
                ];
                // var_dump($data);
                // exit;

                if (VoteResponse::hasUserVoted($data['vote_id'], $data['user_id'])) {
                    echo "لقد قمت بالتصويت مسبقًا";
                    exit;
                }
                if (VoteResponse::createVoteResponse($data)) {
                    header('Location: VoteController.php?action=list&project_id=' . $data['project_id']);
                    exit;
                } else {
                    throw new Exception("فشل في إرسال التصويت");
                }

            } catch (Exception $e) {
                    echo "خطأ في التصويت: " . $e->getMessage();
                }
            }
    }

    // public function resultAction()
    // {
    //     $vote_id = $_GET['vote_id'] ?? 0;

    //     $vote = new Vote();
    //     $voteData = $vote->getVoteById($vote_id);

    //     $response = new VoteResponse();
    //     $results = $response->getResultsByVoteId($vote_id);

    //     include __DIR__ . '/../Views/voteResult.php';
    // }
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
    // case 'result':
    //     $controller->resultAction();
        break;
    case 'list':
    default:
        $controller->listAction();
        break;
}
