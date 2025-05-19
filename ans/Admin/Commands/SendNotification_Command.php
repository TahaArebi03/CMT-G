<?php
class SendNotification_Command {
    private $conn;
    private $message;

    public function __construct($conn, $message) {
        $this->conn = $conn;
        $this->message = trim($message);
    }

    public function sendToUser($user_id) {
        $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user_id, $this->message]);
    }

    public function sendToAll($students) {
        foreach ($students as $student) {
            $this->sendToUser($student['user_id']);
        }
    }
}
