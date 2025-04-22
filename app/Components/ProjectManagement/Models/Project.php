<?php
// File: /app/Components/ProjectManagement/Models/Project.php

require_once __DIR__ . '/../../../../config/config.php';

class Project
{
    private $project_id;
    private $title;
    private $description;
    private $objectives;
    private $deadline;    // string في شكل 'YYYY-MM-DD HH:MM:SS'
    private $status;      // 'active' أو 'archived'
    private $created_by;  // user_id

    public function __construct() {}

    // ——— Getters & Setters ———

    public function getId()            { return $this->project_id; }
    public function getTitle()         { return $this->title; }
    public function setTitle($t)       { $this->title = $t; }

    public function getDescription()   { return $this->description; }
    public function setDescription($d) { $this->description = $d; }

    public function getObjectives()    { return $this->objectives; }
    public function setObjectives($o)  { $this->objectives = $o; }

    public function getDeadline()      { return $this->deadline; }
    public function setDeadline($d)    { $this->deadline = $d; }

    public function getStatus()        { return $this->status; }
    public function setStatus($s)      { $this->status = $s; }

    public function getCreatedBy()     { return $this->created_by; }
    public function setCreatedBy($u)   { $this->created_by = $u; }

    /**
     * تنفيذ INSERT أو UPDATE.
     */
    public function save(): bool
    {
        $db  = new Connect();
        $pdo = $db->conn;

        if ($this->project_id) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE projects
               SET title = ?, description = ?, objectives = ?, deadline = ?, status = ?
               WHERE project_id = ?"
            );
            return $stmt->execute([
                $this->title,
                $this->description,
                $this->objectives,
                $this->deadline,
                $this->status,
                $this->project_id
            ]);
        } else {
            // إدراج جديد
            $stmt = $pdo->prepare(
              "INSERT INTO projects
               (title, description, objectives, deadline, status, created_by)
               VALUES (?, ?, ?, ?, ?, ?)"
            );
            $ok = $stmt->execute([
                $this->title,
                $this->description,
                $this->objectives,
                $this->deadline,
                $this->status,
                $this->created_by
            ]);
            if ($ok) {
                $this->project_id = $pdo->lastInsertId();
            }
            return $ok;
        }
    }

    /**
     * أنشئ مشروعاً جديداً وارجع الكائن.
     */
    public static function createProject(array $data): ? Project
    {
        $p = new Project();
        $p->setTitle($data['title'] ?? '');
        $p->setDescription($data['description'] ?? '');
        $p->setObjectives($data['objectives'] ?? '');
        $p->setDeadline($data['deadline'] ?? date('Y-m-d H:i:s'));
        $p->setStatus($data['status'] ?? 'active');
        $p->setCreatedBy($data['created_by'] ?? null);

        return $p->save() ? $p : null;
    }

    /**
     * حدّث بيانات المشروع الحالي.
     */
    public function updateProject(): bool
    {
        return $this->save();
    }

    /**
     * أشرِف المشروع (archive).
     */
    public function archiveProject(): bool
    {
        $this->setStatus('archived');
        return $this->save();
    }

    /**
     * ارجع تفاصيل المشروع كمصفوفة.
     */
    public function getDetails(): array
    {
        return [
            'project_id'  => $this->project_id,
            'title'       => $this->title,
            'description' => $this->description,
            'objectives'  => $this->objectives,
            'deadline'    => $this->deadline,
            'status'      => $this->status,
            'created_by'  => $this->created_by
        ];
    }

    /**
     * استرجاع مشروع بواسطة المعرف.
     */
    public static function findById(int $id): ?Project
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        // مفروض - set -بش - details - تكون - get
        $p = new Project();
        $p->project_id   = $row['project_id'];
        $p->title        = $row['title'];
        $p->description  = $row['description'];
        $p->objectives   = $row['objectives'];
        $p->deadline     = $row['deadline'];
        $p->status       = $row['status'];
        $p->created_by   = $row['created_by'];
        return $p;
    }
}
