<?php
// ✅ اختبار: StudentTasksTest.php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Config/connect.php';

class StudentTasksTest extends TestCase {
    private $conn;
    private $userId;
    private $taskId;

    protected function setUp(): void {
        // الاتصال وإنشاء مستخدم وهمي
        $db = new Connect();
        $this->conn = $db->conn;

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $email = 'student_' . uniqid() . '@example.com';
        $stmt->execute(['طالب تجريبي', $email, 'pass123', 'طالب']);
        $this->userId = $this->conn->lastInsertId();

        // إنشاء مشروع وهمي
        $this->conn->exec("INSERT INTO projects (title, description, objectives, deadline, status, created_by)
            VALUES ('مشروع وهمي', 'تجريبي', 'اختبار', '2025-12-31', 'نشط', $this->userId)");
        $project_id = $this->conn->lastInsertId();

        // إنشاء مهمة لهذا المستخدم
        $stmt = $this->conn->prepare("INSERT INTO tasks (project_id, title, description, assigned_to, status, priority, deadline, allow_comments)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$project_id, 'مهمة اختبار', 'وصف', $this->userId, 'لم تبدأ', 'عالية', '2025-12-01', true]);
        $this->taskId = $this->conn->lastInsertId();
    }

    // ✅ اختبار تحديث حالة المهمة
    public function testUpdateTaskStatus(): void {
        $stmt = $this->conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
        $result = $stmt->execute(['مكتملة', $this->taskId]);
        $this->assertTrue($result);
    }

    // ✅ اختبار إضافة تعليق
    public function testAddComment(): void {
        $stmt = $this->conn->prepare("INSERT INTO comments (task_id, user_id, content) VALUES (?, ?, ?)");
        $result = $stmt->execute([$this->taskId, $this->userId, 'هذا تعليق اختباري']);
        $this->assertTrue($result);
    }

    protected function tearDown(): void {
        $this->conn->exec("DELETE FROM comments WHERE user_id = $this->userId");
        $this->conn->exec("DELETE FROM tasks WHERE assigned_to = $this->userId");
        $this->conn->exec("DELETE FROM projects WHERE created_by = $this->userId");
        $this->conn->exec("DELETE FROM users WHERE user_id = $this->userId");
    }
}

//vendor\bin\phpunit testing/StudentTasksTest.php
