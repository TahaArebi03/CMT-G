<?php
// File: /app/Components/UserManagement/Models/StudentUser.php
require_once 'User.php';
require_once '../../../config/config.php';

class StudentUser extends User {
    private $studentId;
    private $major;
    private $enrollmentYear;

    public function __construct() {
        parent::__construct();
    }

    public function getStudentId()     { return $this->studentId; }
    public function setStudentId($id)  { $this->studentId = $id; }
    public function getMajor()         { return $this->major; }
    public function setMajor($m)       { $this->major = $m; }
    public function getEnrollmentYear(){ return $this->enrollmentYear; }
    public function setEnrollmentYear($y){ $this->enrollmentYear = $y; }

    /**
     * استرجاع المشاريع الملتحق بها الطالب.
     */
    public function getEnrolledProjects() {
        $projects = [];
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $stmt = $conn->prepare(
          "SELECT p.project_id, p.title 
           FROM projects p
           JOIN project_members pm ON p.project_id = pm.project_id
           WHERE pm.user_id = ?"
        );
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $projects[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $projects;
    }

    /**
     * تسليم واجب – مجرد ديمو.
     */
    public function submitAssignment() {
        // هنا يمكن إضافة منطق تسليم الواجب (مثل تخزين سجل في جدول assignments)
    }
}
