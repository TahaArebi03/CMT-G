<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Config/connect.php';

class StudentVoteTest extends TestCase {
    private $conn;
    private $userId;
    private $voteId;

    // إنشاء اتصال وقيم وهمية
    protected function setUp(): void {
        $db = new Connect();
        $this->conn = $db->conn;

        // إنشاء مستخدم وهمي
        $email = 'student_' . uniqid() . '@test.com';
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['طالب', $email, 'test123', 'طالب']);
        $this->userId = $this->conn->lastInsertId();

        // إنشاء تصويت
        $question = 'ما أفضل لغة برمجة؟';
        $options = json_encode(['PHP', 'Python', 'Java']);
        $stmt2 = $this->conn->prepare("INSERT INTO votes (project_id, question, options, status, created_by) VALUES (?, ?, ?, 'مفتوح', ?)");
        $stmt2->execute([1, $question, $options, $this->userId]);
        $this->voteId = $this->conn->lastInsertId();
    }

    // اختبار عملية التصويت
    public function testStudentVote(): void {
        $stmt = $this->conn->prepare("INSERT INTO vote_responses (vote_id, user_id, selected_option) VALUES (?, ?, ?)");
        $result = $stmt->execute([$this->voteId, $this->userId, 'PHP']);

        $this->assertTrue($result);
    }

    // حذف البيانات بعد كل اختبار
    protected function tearDown(): void {
        $this->conn->exec("DELETE FROM vote_responses WHERE user_id = {$this->userId}");
        $this->conn->exec("DELETE FROM votes WHERE vote_id = {$this->voteId}");
        $this->conn->exec("DELETE FROM users WHERE user_id = {$this->userId}");
    }
}

//vendor\bin\phpunit testing/StudentVoteTest.php
