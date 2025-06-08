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
          "SELECT * FROM users WHERE role = 'student' and project_id IS NULL"
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


    // الاسترجاع ممكن يكون من projectMember .
    // استرجاع الطلبة الموجودين في المشروع .
    public static function findByProject(int $projectId): array
    {
        try{
        $db  = new Connect();
        $pdo = $db->conn;
        $sql = "
          SELECT *
          FROM users WHERE role = 'Student' and project_id = ?;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$projectId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $students = [];
        foreach ($rows as $row) {
            $s= new StudentUser();
            $s->setName($row['name']);
            $s->setUserId($row['user_id']);
            $s->setMajor($row['major']);
            $s->setProjectId($row['project_id']?? null);
            $students[] = $s;
        }
        return $students;
    } catch (PDOException $e) {
            error_log("FindByProject Error: " . $e->getMessage());
            return [];
        }
    }

}
