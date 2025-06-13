<?php
require_once __DIR__ . '/../../../../config/config.php';
class Task
{
    private $task_id;
    private $project_id;
    private $title;
    private $description;
    private $assigned_to;  // user_id
    private $status;      // not_started, in_progress, completed, in_review
    private $priority;    // high, medium, low
    private $deadline;    // YYYY-MM-DD HH:MM:SS
    // ——— Getters & Setters ———
    public function getTaskId()      { return $this->task_id; }
    public function getProjectId()   { return $this->project_id; }
    public function getTitle()       { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getAssignedTo()  { return $this->assigned_to; }
    public function getStatus()      { return $this->status; }
    public function getPriority()    { return $this->priority; }
    public function getDeadline()    { return $this->deadline; }
    public function setTaskId($id)         { $this->task_id     = $id; }
    public function setProjectId($id)      { $this->project_id  = $id; }
    public function setTitle($t)            { $this->title       = $t; }
    public function setDescription($d)      { $this->description = $d; }
    public function setAssignedTo($u)       { $this->assigned_to  = $u; }
    public function setStatus($s)           { $this->status      = $s; }
    public function setPriority($p)         { $this->priority    = $p; }
    public function setDeadline($dt)        { $this->deadline    = $dt; }
    /**
     * احفظ المهمة (INSERT أو UPDATE)
     */
    public function save(): bool
    {
        try{
        $db  = new Connect();
        $pdo = $db->conn;

        if ($this->task_id) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE tasks
               SET title = ?, description = ?, assigned_to = ?, status = ?, priority = ?, deadline = ?
               WHERE task_id = ?"
            );
            return $stmt->execute([
                $this->title,
                $this->description,
                $this->assigned_to,
                $this->status,
                $this->priority,
                $this->deadline,
                $this->task_id
            ]);
        } else {
            // إدراج جديد
            $stmt = $pdo->prepare(
              "INSERT INTO tasks
               (project_id, title, description, assigned_to, status, priority, deadline)
               VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $ok = $stmt->execute([
                $this->project_id,
                $this->title,
                $this->description,
                $this->assigned_to,
                $this->status,
                $this->priority,
                $this->deadline
            ]);
            if ($ok) {
                $this->task_id = $pdo->lastInsertId();
            }
            return $ok;
        }
        } catch (PDOException $e) {
            // التعامل مع الأخطاء
            error_log("Error saving task: " . $e->getMessage());
            return false;
        }
    }

    /**
     * جلب كل المهام الخاصة بمشروع معيّن
     * @return Task[]
     */
    public static function findByProjectId(int $project_id): array
    {
        try{
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
            $t->task_id      = $row['task_id'];
            $t->project_id   = $row['project_id'];
            $t->title       = $row['title'];
            $t->description = $row['description'];
            $t->assigned_to  = $row['assigned_to'];
            $t->status      = $row['status'];
            $t->priority    = $row['priority'];
            $t->deadline    = $row['deadline'];
            $tasks[] = $t;
        }
        return $tasks;
        } catch (PDOException $e) {
            // التعامل مع الأخطاء
            error_log("Error fetching tasks: " . $e->getMessage());
            return [];
        }
    }

    /**
     * جلب مهمة واحدة حسب المُعرّف
     */
    public static function findById(int $taskId): ?Task
    {
        try{
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_id = ?");
        $stmt->execute([$taskId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $t = new Task();
        $t->task_id      = $row['task_id'];
        $t->project_id   = $row['project_id'];
        $t->title       = $row['title'];
        $t->description = $row['description'];
        $t->assigned_to  = $row['assigned_to'];
        $t->status      = $row['status'];
        $t->priority    = $row['priority'];
        $t->deadline    = $row['deadline'];
        return $t;
        } catch (PDOException $e) {
            // التعامل مع الأخطاء
            error_log("Error fetching task by ID: " . $e->getMessage());
            return null;
        }
    }

    // تحديد اولويات المهام
    public static function orderByPriority(array $tasks): array
    {
        try{
        usort($tasks, function ($a, $b) {
            $order_priority = ['high' => 1, 'medium' => 2, 'low' => 3];
            return $order_priority[$a->getPriority()] <=> $order_priority[$b->getPriority()];
        });
        return $tasks;
        } catch (Exception $e) {
            // التعامل مع الأخطاء
            error_log("Error ordering tasks by priority: " . $e->getMessage());
            return $tasks; // إرجاع المهام كما هي في حالة حدوث خطأ
        }
    }
    public function start()
{
    $this->status = 'in_progress';
    // تحديث في قاعدة البيانات
    $db = new Connect();
    $pdo = $db->conn;
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'in_progress' WHERE task_id = ?");
    $stmt->execute([$this->task_id]);
}

public function complete()
{
    try{
    $this->status = 'completed';
    $db = new Connect();
    $pdo = $db->conn;
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed' WHERE task_id = ?");
    $stmt->execute([$this->task_id]);
    } catch (PDOException $e) {
        // التعامل مع الأخطاء
        error_log("Error completing task: " . $e->getMessage());
    }
}


}
