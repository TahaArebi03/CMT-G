<?php
// File: /app/Components/UserManagement/Models/StudentUser.php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/User.php';

class StudentUser extends User {
    private $studentId;
    private $major;
    private $enrollmentYear;

    // getters / setters...
    public function getStudentId()      { return $this->studentId; }
    public function setStudentId($id)   { $this->studentId = $id; }
    public function getMajor()          { return $this->major; }
    public function setMajor($m)        { $this->major = $m; }
    public function getEnrollmentYear() { return $this->enrollmentYear; }
    public function setEnrollmentYear($y) { $this->enrollmentYear = $y; }

    /**
     * استرجاع المشاريع الملتحق بها الطالب
     */
    public function getEnrolledProjects() {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "SELECT p.project_id, p.title
           FROM projects p
           JOIN project_members pm ON p.project_id = pm.project_id
           WHERE pm.user_id = ?"
        );
        $stmt->execute([$this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * تسليم واجب
     */
    public function submitAssignment() {
        // منطق تسليم الواجب
    }
}
