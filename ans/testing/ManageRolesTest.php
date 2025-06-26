<?php
// ✅ ملف: testing/ManageRolesTest.php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Config/connect.php';

class ManageRolesTest extends TestCase {
    private $conn;
    private $userId;

    protected function setUp(): void {
        $db = new Connect();
        $this->conn = $db->conn;

        // إنشاء مستخدم افتراضي
        $email = 'testuser_' . uniqid() . '@example.com';
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Test User', $email, password_hash('secret', PASSWORD_DEFAULT), 'طالب']);
        $this->userId = $this->conn->lastInsertId();
    }

    public function testChangeUserRole(): void {
        $stmt = $this->conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $result = $stmt->execute(['مسؤول', $this->userId]);

        $this->assertTrue($result);
    }

    public function testDeleteUser(): void {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
        $result = $stmt->execute([$this->userId]);

        $this->assertTrue($result);
    }

    protected function tearDown(): void {
        // تنظيف في حال لم يتم الحذف
        $this->conn->exec("DELETE FROM users WHERE user_id = {$this->userId}");
    }
}
//vendor\bin\phpunit testing/ManageRolesTest.php
