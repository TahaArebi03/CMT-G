<?php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/User.php';

class StudentUser extends User {

    // عرض الطلبة لاضافتهم في المشروع
    public static function findAllStudents(): array
    {
        try{
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "SELECT * FROM users WHERE role = 'Student' and project_id IS NULL"
        );
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $students = [];
        foreach ($rows as $row) {
            $s = new StudentUser();
            $s->setUserId($row['user_id']);
            $s->setName($row['name']);
            $s->setEmail($row['email']);
            $s->setMajor($row['major']);
            $s->setProjectId($row['project_id'] ?? null);
            $students[] = $s;
        }
        return $students;
    } catch (PDOException $e) {
            error_log("FindAllStudents Error: " . $e->getMessage());
            return [];
        }
    }


}
