<?php

require_once __DIR__ . '/../../../../config/config.php';

class Task
{
    private $taskId;
    private $projectId;
    private $title;
    private $description;
    private $assignedTo;  // user_id
    private $status;      // not_started, in_progress, completed, in_review
    private $priority;    // high, medium, low
    private $deadline;    // YYYY-MM-DD HH:MM:SS

    // ——— Getters & Setters ———
    public function getTaskId()      { return $this->taskId; }
    public function getProjectId()   { return $this->projectId; }
    public function getTitle()       { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getAssignedTo()  { return $this->assignedTo; }
    public function getStatus()      { return $this->status; }
    public function getPriority()    { return $this->priority; }
    public function getDeadline()    { return $this->deadline; }

    public function setProjectId($id)      { $this->projectId   = $id; }
    public function setTitle($t)            { $this->title       = $t; }
    public function setDescription($d)      { $this->description = $d; }
    public function setAssignedTo($u)       { $this->assignedTo  = $u; }
    public function setStatus($s)           { $this->status      = $s; }
    public function setPriority($p)         { $this->priority    = $p; }
    public function setDeadline($dt)        { $this->deadline    = $dt; }

    /**
     * احفظ المهمة (INSERT أو UPDATE)
     */
    public function save(): bool
    {
        $db  = new Connect();
        $pdo = $db->conn;

        if ($this->taskId) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE tasks
               SET title = ?, description = ?, assigned_to = ?, status = ?, priority = ?, deadline = ?
               WHERE task_id = ?"
            );
            return $stmt->execute([
                $this->title,
                $this->description,
                $this->assignedTo,
                $this->status,
                $this->priority,
                $this->deadline,
                $this->taskId
            ]);
        } else {
            // إدراج جديد
            $stmt = $pdo->prepare(
              "INSERT INTO tasks
               (project_id, title, description, assigned_to, status, priority, deadline)
               VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $ok = $stmt->execute([
                $this->projectId,
                $this->title,
                $this->description,
                $this->assignedTo,
                $this->status,
                $this->priority,
                $this->deadline
            ]);
            if ($ok) {
                $this->taskId = $pdo->lastInsertId();
            }
            return $ok;
        }
    }

    /**
     * جلب كل المهام الخاصة بمشروع معيّن
     * @return Task[]
     */
    public static function findByProjectId(int $project_id): array
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare(
          "SELECT * FROM tasks WHERE project_id = ? ORDER BY deadline ASC"
        );
        $stmt->execute([$project_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($rows as $row) {
            $t = new Task();
            $t->taskId      = $row['task_id'];
            $t->projectId   = $row['project_id'];
            $t->title       = $row['title'];
            $t->description = $row['description'];
            $t->assignedTo  = $row['assigned_to'];
            $t->status      = $row['status'];
            $t->priority    = $row['priority'];
            $t->deadline    = $row['deadline'];
            $tasks[] = $t;
        }
        return $tasks;
    }

    /**
     * جلب مهمة واحدة حسب المُعرّف
     */
    public static function findById(int $taskId): ?Task
    {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_id = ?");
        $stmt->execute([$taskId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $t = new Task();
        $t->taskId      = $row['task_id'];
        $t->projectId   = $row['project_id'];
        $t->title       = $row['title'];
        $t->description = $row['description'];
        $t->assignedTo  = $row['assigned_to'];
        $t->status      = $row['status'];
        $t->priority    = $row['priority'];
        $t->deadline    = $row['deadline'];
        return $t;
    }
}
