<?php
// ✅ ملف: testing/ManageProjectsTest.php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Config/connect.php';

class ManageProjectsTest extends TestCase {
    private $conn;
    private $userId;

    protected function setUp(): void {
        $db = new Connect();
        $this->conn = $db->conn;

        // إنشاء مستخدم وهمي لاختبار created_by (ببريد فريد)
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $email = 'tester_' . uniqid() . '@example.com';
        $stmt->execute(['Tester', $email, 'pass123', 'مسؤول']);
        $this->userId = $this->conn->lastInsertId();
    }

    public function testCreateProject(): void {
        $stmt = $this->conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute(['اختبار', 'وصف المشروع', 'أهداف المشروع', '2025-12-31', 'نشط', $this->userId]);
        $this->assertTrue($result);
    }

    public function testUpdateProject(): void {
        // أضف مشروع أولاً
        $this->conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)")
                   ->execute(['قبل التعديل', 'desc', 'obj', '2025-01-01', 'نشط', $this->userId]);
        $pid = $this->conn->lastInsertId();

        // عدل المشروع
        $stmt = $this->conn->prepare("UPDATE projects SET title=?, status=? WHERE project_id=?");
        $result = $stmt->execute(['بعد التعديل', 'مؤرشف', $pid]);

        $this->assertTrue($result);
    }

    public function testDeleteProject(): void {
        $this->conn->prepare("INSERT INTO projects (title, description, objectives, deadline, status, created_by) VALUES (?, ?, ?, ?, ?, ?)")
                   ->execute(['للحذف', 'desc', 'obj', '2025-01-01', 'نشط', $this->userId]);
        $pid = $this->conn->lastInsertId();

        $stmt = $this->conn->prepare("DELETE FROM projects WHERE project_id=?");
        $result = $stmt->execute([$pid]);

        $this->assertTrue($result);
    }

    protected function tearDown(): void {
        // حذف مشاريع المستخدم والمستخدم الوهمي
        $this->conn->exec("DELETE FROM projects WHERE created_by = $this->userId");
        $this->conn->exec("DELETE FROM users WHERE user_id = $this->userId");
    }
}

//vendor\bin\phpunit testing\ManageProjectsTest.php
