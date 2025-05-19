<?php
class TaskFacade {
    private $conn;
    private $user_id;

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    public function fetchTasks($keyword, $before_date) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT tasks.*, projects.title AS project_title FROM tasks
                JOIN projects ON tasks.project_id = projects.project_id
                WHERE assigned_to = ? AND tasks.title LIKE ?";
        $params = [$this->user_id, $keyword];

        if ($before_date) {
            $sql .= " AND tasks.deadline <= ?";
            $params[] = $before_date;
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ فشل في جلب المهام: " . $e->getMessage() . "</p>";
            return [];
        }
    }

    public function fetchComments() {
        $comments_map = [];
        try {
            $stmt = $this->conn->query("SELECT comments.*, users.name FROM comments JOIN users ON users.user_id = comments.user_id ORDER BY created_at ASC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $comments_map[$row['task_id']][] = $row;
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ فشل في جلب التعليقات: " . $e->getMessage() . "</p>";
        }
        return $comments_map;
    }

    public function addComment($task_id, $content) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO comments (task_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$task_id, $this->user_id, $content]);
            echo "<p style='color:green;'>✅ تم إرسال التعليق بنجاح</p>";
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ حدث خطأ: " . $e->getMessage() . "</p>";
        }
    }

    // ✅ الوظيفة الجديدة لتحديث حالة المهمة
    public function updateTaskStatus($task_id, $status) {
        $allowed_statuses = ['قيد التنفيذ', 'مكتملة'];
        if (!in_array($status, $allowed_statuses)) {
            return; // تجاهل القيم غير المسموحة
        }

        try {
            $stmt = $this->conn->prepare("UPDATE tasks SET status = ? WHERE task_id = ? AND assigned_to = ?");
            $stmt->execute([$status, $task_id, $this->user_id]);
        } catch (PDOException $e) {
            echo "<p style='color:red;'>❌ خطأ أثناء تحديث الحالة: " . $e->getMessage() . "</p>";
        }
    }
}
