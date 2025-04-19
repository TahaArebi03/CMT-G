<?php
// File: /app/Components/ProjectManagement/Models/ProjectMember.php

require_once __DIR__ . '/../../../../config/config.php';

class ProjectMember
{
    private $project_id;
    private $user_id;
    private $role_in_project; // 'member', 'team_leader', إلخ

    public function __construct() {}

    public function getProjectId()        { return $this->project_id; }
    public function getUserId()           { return $this->user_id; }
    public function getRoleInProject()    { return $this->role_in_project; }

    public function setProjectId($pid)    { $this->project_id = $pid; }
    public function setUserId($uid)       { $this->user_id = $uid; }
    public function setRoleInProject($r)  { $this->role_in_project = $r; }

    /**
     * استرجاع معلومات العضوية كمصفوفة.
     */
    public function getMemberInfo(): array
    {
        return [
            'project_id'       => $this->project_id,
            'user_id'          => $this->user_id,
            'role_in_project'  => $this->role_in_project
        ];
    }

    /**
     * تحديث دور العضو في المشروع.
     */
    public function updateRole(string $newRole): bool
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "UPDATE project_members
           SET role_in_project = ?
           WHERE project_id = ? AND user_id = ?"
        );
        return $stmt->execute([
            $newRole,
            $this->project_id,
            $this->user_id
        ]);
    }

    /**
     * إضافة عضو جديد إلى مشروع.
     */
    public function save(): bool
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "REPLACE INTO project_members
           (project_id, user_id, role_in_project)
           VALUES (?, ?, ?)"
        );
        return $stmt->execute([
            $this->project_id,
            $this->user_id,
            $this->role_in_project
        ]);
    }

    /**
     * استرجاع كل الأعضاء لمشروع معيّن.
     * @return ProjectMember[]
     */
    public static function findByProjectId(int $projectId): array
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "SELECT * FROM project_members WHERE project_id = ?"
        );
        $stmt->execute([$projectId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $members = [];
        foreach ($rows as $row) {
            $m = new ProjectMember();
            $m->project_id      = $row['project_id'];
            $m->user_id         = $row['user_id'];
            $m->role_in_project = $row['role_in_project'];
            $members[] = $m;
        }
        return $members;
    }
}
