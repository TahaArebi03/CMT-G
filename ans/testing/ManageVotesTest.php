<?php
// ✅ ملف اختبار لإدارة التصويتات: إنشاء وحذف التصويت
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Config/connect.php';

class ManageVotesTest extends TestCase {
    private $conn;
    private $userId;

    // 🧪 إعداد قاعدة البيانات: إنشاء اتصال وإضافة مستخدم مسؤول
    protected function setUp(): void {
        $db = new Connect();
        $this->conn = $db->conn;

        // إنشاء مستخدم وهمي (مسؤول)
        $email = 'tester_' . uniqid() . '@example.com';
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Tester', $email, 'pass123', 'مسؤول']);
        $this->userId = $this->conn->lastInsertId();
    }

    // 🧪 اختبار: إنشاء تصويت جديد في قاعدة البيانات
    public function testCreateVote(): void {
        $options = json_encode(['نعم', 'لا'], JSON_UNESCAPED_UNICODE); // خيارات التصويت بصيغة JSON
        $stmt = $this->conn->prepare("INSERT INTO votes (project_id, question, options, status, created_by) VALUES (?, ?, ?, 'مفتوح', ?)");
        $result = $stmt->execute([1, 'هل تؤيد الخطة؟', $options, $this->userId]);

        $this->assertTrue($result); // ✅ تأكيد نجاح الإضافة
    }

    // 🧪 اختبار: حذف تصويت موجود من قاعدة البيانات
    public function testDeleteVote(): void {
        // إنشاء تصويت أولاً
        $options = json_encode(['أوافق', 'لا أوافق'], JSON_UNESCAPED_UNICODE);
        $stmt = $this->conn->prepare("INSERT INTO votes (project_id, question, options, status, created_by) VALUES (?, ?, ?, 'مفتوح', ?)");
        $stmt->execute([1, 'هل توافق؟', $options, $this->userId]);
        $voteId = $this->conn->lastInsertId();

        // حذف التصويت
        $stmt2 = $this->conn->prepare("DELETE FROM votes WHERE vote_id = ?");
        $result = $stmt2->execute([$voteId]);

        $this->assertTrue($result); // ✅ تأكيد نجاح الحذف
    }

    // 🧹 تنظيف بعد كل اختبار: حذف التصويتات والردود والمستخدم الوهمي
    protected function tearDown(): void {
        $this->conn->exec("DELETE FROM vote_responses");
        $this->conn->exec("DELETE FROM votes WHERE created_by = $this->userId");
        $this->conn->exec("DELETE FROM users WHERE user_id = $this->userId");
    }
}

//vendor\bin\phpunit testing\ManageVotesTest.php
