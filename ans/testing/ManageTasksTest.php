<?php
// ✅ ملف: testing/ManageTasksTest.php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Config/connect.php';

class ManageTasksTest extends TestCase {
    private $conn;
    private $userId;
    private $projectId;

    protected function setUp(): void {
        $db = new Connect();
        $this->conn = $db->conn;

        // إنشاء مستخدم
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $email = 'tester_' . uniqid() . '@example.com';
        $stmt->execute(['Tester', $email, 'pass123', 'طالب']);
        $this->userId = $this->conn->lastInsertId();

        // إنشاء مشروع
        $stmt = $this->conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['اختبار', 'desc', 'obj', '2025-12-01', 'نشط', $this->userId]);
        $this->projectId = $this->conn->lastInsertId();
    }

    public function testAddTask(): void {
        $stmt = $this->conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline, allow_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$this->projectId, 'عنوان', 'شرح', $this->userId, 'لم تبدأ', 'عالية', '2025-12-31', 1]);

        $this->assertTrue($result);
    }

    public function testUpdateTask(): void {
        $this->conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline, allow_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$this->projectId, 'قبل', 'desc', $this->userId, 'لم تبدأ', 'منخفضة', '2025-12-01', 1]);
        $taskId = $this->conn->lastInsertId();

        $stmt = $this->conn->prepare("UPDATE tasks SET title=?, status=? WHERE task_id=?");
        $result = $stmt->execute(['بعد', 'مكتملة', $taskId]);

        $this->assertTrue($result);
    }

    public function testDeleteTask(): void {
        $this->conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline, allow_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$this->projectId, 'للحذف', 'desc', $this->userId, 'لم تبدأ', 'منخفضة', '2025-12-01', 1]);
        $taskId = $this->conn->lastInsertId();

        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE task_id=?");
        $result = $stmt->execute([$taskId]);

        $this->assertTrue($result);
    }

    protected function tearDown(): void {
        $this->conn->exec("DELETE FROM tasks WHERE assigned_to = {$this->userId}");
        $this->conn->exec("DELETE FROM projects WHERE created_by = {$this->userId}");
        $this->conn->exec("DELETE FROM users WHERE user_id = {$this->userId}");
    }
}
//vendor\bin\phpunit testing\ManageTasksTest.php
