<?php
require_once __DIR__ . '../../../../../config/config.php';

class VoteResponse {
    private $response_id; 
    private $vote_id;
    private $user_id;
    private $selected_option;

    public function setVoteId($id) {
        $this->vote_id = $id;
    }

    public function setUserId($id) {
        $this->user_id = $id;
    }

    public function setSelectedOption($option) {
        $this->selected_option = $option;
    }

    public function getVoteId() {
        return $this->vote_id;
    }
    public function getUserId() {
        return $this->user_id;
    }
    public function getSelectedOption() {
        return $this->selected_option;
    }

    public function save() {
        $db = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("INSERT INTO vote_responses (vote_id, user_id, selected_option) VALUES (?, ?, ?)");
        $ok = $stmt->execute([
            $this->vote_id,
            $this->user_id,
            $this->selected_option
        ]);
        if ($ok) {
            $this->response_id = $pdo->lastInsertId();
            
            return $ok;
        }
        if (!$ok) {
    $errorInfo = $stmt->errorInfo();
    echo "خطأ في تخزين التصويت: " . $errorInfo[2];
}

    }

    public static function createVoteResponse($data) {
        $response = new VoteResponse();
        $response->setVoteId($data['vote_id']);
        $response->setUserId($data['user_id']);
        $response->setSelectedOption($data['selected_option']);
        
        
        return $response->save()? $response : null;
    }

 

    public static function getUserVoteForVote($voteId, $userId) {
    $db = new Connect();
    $pdo = $db->conn;

    $stmt = $pdo->prepare("SELECT selected_option FROM vote_responses WHERE vote_id = ? AND user_id = ?");
    $stmt->execute([$voteId, $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($result);
    // exit;
    return $result ? $result['selected_option'] : null;
}


    public static function hasUserVoted($voteId, $userId) {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("SELECT * FROM vote_responses WHERE vote_id = ? AND user_id = ?");
        $stmt->execute([$voteId, $userId]);
        return $stmt->fetch() !== false;
    }

   

    public function getResults($voteId) {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("SELECT selected_option, COUNT(*) as count FROM vote_responses WHERE vote_id = ? GROUP BY selected_option");
        $stmt->execute([$voteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
