<?php

require_once __DIR__ . '/../../../../config/config.php';

class ProjectMember
{
   


    /**
     * تحديث دور العضو في المشروع.
     */
    // public function updateRole(string $newRole): bool
    // {
    //     $db  = new Connect();
    //     $pdo = $db->conn;
    //     $stmt = $pdo->prepare(
    //       "UPDATE project_members
    //        SET role_in_project = ?
    //        WHERE project_id = ? AND user_id = ?"
    //     );
    //     return $stmt->execute([
    //         $newRole,
    //         // $this->project_id,
    //         // $this->user_id
    //     ]);
    // }

    /**
     * إضافة عضو جديد إلى مشروع.
     */
    // public function save(): bool
    // {
    //     $db  = new Connect();
    //     $pdo = $db->conn;
    //     $stmt = $pdo->prepare(
    //       "REPLACE INTO project_members
    //        (project_id, user_id, role_in_project)
    //        VALUES (?, ?, ?)"
    //     );
    //     return $stmt->execute([
    //         // $this->project_id,
    //         // $this->user_id,
    //         // $this->role_in_project
    //     ]);
    // }

    // استرجاع كل الأعضاء لمشروع معيّن.
        
    public static function findByProjectId(int $project_id): array
    {
        try{
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE project_id = ?"
        );
        $stmt->execute([$project_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $members = [];
        foreach ($rows as $row) {
            $s=new User();
            $s->setName($row['name']);
            $s->setRole($row['role']);
            $s->setMajor($row['major']);
            $s->setProjectId($row['project_id']);
            
            $members[]=$s;
        }
        return $members;
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
