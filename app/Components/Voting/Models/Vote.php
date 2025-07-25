<?php
include_once __DIR__ . '../../../../../config/config.php';
class Vote {
    private $vote_id;
    private $project_id;
    private $question;
    private $status;  // 'open' or 'closed'
    private $created_by; // User ID of the creator



    public function __construct() {
    }
    // ——— Getters & Setters ———
    public function getVoteId() {
        return $this->vote_id;
    }

    public function getProjectId() {
        return $this->project_id;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }
    public function setVoteId($id) {
        $this->vote_id = $id;
    }
    public function setProjectId($id) {
        $this->project_id = $id;
    }

    public function setQuestion($question) {
        $this->question = $question;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setCreatedBy($createdBy) {
        $this->created_by = $createdBy;
    }

    public function createVote() {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("INSERT INTO votes (project_id, question, status, created_by) VALUES (?, ?, ?, ?)");
        $ok= $stmt->execute([
            $this->project_id,
            $this->question,
            $this->status,
            $this->created_by
        ]);
        if ($ok) {
            $this->vote_id = $pdo->lastInsertId();
            
            // Get the last inserted ID
            return $ok;
        }
    }

    public function getVoteById($voteId) {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("SELECT * FROM votes WHERE vote_id = ?");
        $stmt->execute([$voteId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllVotesByProject($projectId) {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("SELECT * FROM votes WHERE project_id = ?");
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function closeVote($voteId) {
        $db = new Connect();
        $pdo = $db->conn;

        $stmt = $pdo->prepare("UPDATE votes SET status = 'closed' WHERE vote_id = ?");
        return $stmt->execute([$voteId]);
    }
}
?>
