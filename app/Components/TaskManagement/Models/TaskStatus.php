<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/Task.php';

class TaskStatusUpdater
{
    public static function updateStatus(Task $task, string $newStatus): bool
    {
        try {
            $db = new Connect();
            $pdo = $db->conn;
            $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE task_id = ?");
            $ok = $stmt->execute([$newStatus, $task->getTaskId()]);
            if ($ok) {
                $task->setStatus($newStatus);
            }
            return $ok;
        } catch (PDOException $e) {
            error_log("Status update failed: " . $e->getMessage());
            return false;
        }
    }
}
?>
