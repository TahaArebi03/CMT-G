<?php
// File: /app/Components/UserManagement/Models/AdminUser.php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/User.php';

class AdminUser extends User {
    private $adminLevel;
    private $department;

    public function getAdminLevel()     { return $this->adminLevel; }
    public function setAdminLevel($l)   { $this->adminLevel = $l; }
    public function getDepartment()     { return $this->department; }
    public function setDepartment($d)   { $this->department = $d; }

    /**
     * جلب جميع المستخدمين لإدارتهم
     */
    public function manageUsers() {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->query("SELECT user_id, name, email, role FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * إنشاء تقرير (مثال)
     */
    public function generateReports() {
        // منطق إنشاء التقارير
    }
}
