<?php
// File: /app/Components/UserManagement/Models/AdminUser.php
require_once 'User.php';
require_once '../../../config/config.php';

class AdminUser extends User {
    private $adminLevel;
    private $department;

    public function __construct() {
        parent::__construct();
    }

    public function getAdminLevel()   { return $this->adminLevel; }
    public function setAdminLevel($l) { $this->adminLevel = $l; }
    public function getDepartment()    { return $this->department; }
    public function setDepartment($d) { $this->department = $d; }

    /**
     * جلب جميع المستخدمين لإدارتهم.
     */
    public function manageUsers() {
        $users = [];
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = $conn->query("SELECT user_id, name, email, role FROM users");
        while ($row = $res->fetch_assoc()) {
            $users[] = $row;
        }
        $conn->close();
        return $users;
    }

    /**
     * إنشاء تقرير – مجرد ديمو.
     */
    public function generateReports() {
        // هنا يمكن إضافة منطق إنشاء التقارير (مثل توليد ملف PDF أو تجميع إحصائيات)
    }
}
