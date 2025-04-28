<?php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/User.php';

class StudentUser extends User {
    private $studentId;
    private $major;
    private $enrollmentYear;

    // getters / setters
    public function getStudentId()      { return $this->studentId; }
    public function setStudentId($id)   { $this->studentId = $id; }
    public function getMajor()          { return $this->major; }
    public function setMajor($m)        { $this->major = $m; }
    public function getEnrollmentYear() { return $this->enrollmentYear; }
    public function setEnrollmentYear($y) { $this->enrollmentYear = $y; }




    public static function findAllStudents(): array
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "SELECT * FROM users WHERE role = 'Student'"
        );
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $students = [];
        foreach ($rows as $row) {
            $s = new StudentUser();
            $s->setName($row['name']);
            $s->setEmail($row['email']);
            $s->setProjectId($row['project_id'] ?? null);
            $students[] = $s;
        }
        return $students;
    }



    /**
     * تسليم واجب
     */
    public function submitAssignment() {
        // منطق تسليم الواجب
    }
}
