<?php
require_once __DIR__ . '../../../../../config/config.php';

class VoteResponse {
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

    public function hasUserVoted($voteId, $userId) {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("SELECT * FROM vote_responses WHERE vote_id = ? AND user_id = ?");
        $stmt->execute([$voteId, $userId]);
        return $stmt->fetch() !== false;
    }

    public function submit() {
        $db = new Connect();
        $pdo = $db->conn;

        // Check if already voted
        // $check = $pdo->prepare("SELECT * FROM vote_responses WHERE vote_id = ? AND user_id = ?");
        // $check->execute([$this->vote_id, $this->user_id]);
        // if ($check->fetch()) return false;

        // Submit response
        $stmt = $pdo->prepare("INSERT INTO vote_responses (vote_id, user_id, selected_option) VALUES (?, ?, ?)");
        return $stmt->execute([$this->vote_id, $this->user_id, $this->selected_option]);
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
