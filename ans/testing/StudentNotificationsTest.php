<?php
// ✅ ملف: testing/StudentNotificationsTest.php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Config/connect.php';

class StudentNotificationsTest extends TestCase {
    private $conn;
    private $userId;

    // 🔧 إعداد الاتصال وإنشاء مستخدم وهمي
    protected function setUp(): void {
        $db = new Connect();
        $this->conn = $db->conn;

        // إنشاء مستخدم حقيقي لاختبار الإشعارات
        $email = 'test_student_' . uniqid() . '@example.com';
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['طالب تجريبي', $email, 'pass123', 'طالب']);
        $this->userId = $this->conn->lastInsertId();

        // إدخال إشعار تجريبي لهذا المستخدم
        $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$this->userId, "🔔 إشعار تجريبي"]);
    }

    // ✅ اختبار جلب الإشعارات للمستخدم
    public function testFetchNotifications(): void {
        $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result); // 🔍 تحقق أن الإشعارات غير فارغة
    }

    // 🧹 تنظيف البيانات بعد الاختبار
    protected function tearDown(): void {
        $this->conn->exec("DELETE FROM notifications WHERE user_id = $this->userId");
        $this->conn->exec("DELETE FROM users WHERE user_id = $this->userId");
    }
}


//vendor\bin\phpunit testing\studentnotificationsTest.php 