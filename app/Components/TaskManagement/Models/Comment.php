<?php
require '../../../../config/config.php';
class Comment{
    private $comment_id;
    private $task_id;
    private $user_id;
    private $comment_text;
    private $created_at;
    // public function __construct(){

    // }   
    // ——— Getters & Setters ———
    
    public function getCommentId(){return $this->comment_id;}

    public function getTaskId(){return $this->task_id;}

    public function getUserId(){return $this->user_id;}

    public function getCommentText(){return $this->comment_text;}

    public function getCreatedAt(){return $this->created_at;}
    public function setCommentId($id){$this->comment_id=$id;}
    public function setTaskId($id){$this->task_id=$id;}
    public function setUserId($id){$this->user_id=$id;}
    public function setCommentText($text){$this->comment_text=$text;}
    public function setCreatedAt($date){$this->created_at=$date;}

    // ادراج وتحديث
    public function save(): bool
    {
        $db  = new Connect();
        $pdo = $db->conn;

        if ($this->comment_id) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE comments
               SET task_id = ?, user_id = ?, comment_text = ?, created_at = ?
               WHERE comment_id = ?"
            );
            return $stmt->execute([
                $this->task_id,
                $this->user_id,
                $this->comment_text,
                $this->created_at,
                $this->comment_id
            ]);
        } else {
            // إدراج جديد
            $stmt = $pdo->prepare(
              "INSERT INTO comments (task_id, user_id, comment_text, created_at)
               VALUES (?, ?, ?, ?)"
            );
            $ok= $stmt->execute([
                $this->task_id,
                $this->user_id,
                $this->comment_text,
                $this->created_at
            ]);
            if($ok){
                $this->comment_id = $pdo->lastInsertId();
            }   
            return $ok;
        }
}
    // جلب جميع التعليقات في المهمة
    public function getCommentsByTaskId($task_id){
        $db = new Connect();
        $pdo = $db->conn;
        $stmt= $pdo->prepare("SELECT * FROM comments WHERE task_id=?");
        $stmt->execute([$task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // حذف تعليق
    public function delete($comment_id){
        $db = new Connect();
        $pdo = $db->conn;
        $stmt= $pdo->prepare("DELETE FROM comments WHERE comment_id=?");
        return $stmt->execute([$comment_id]);

    }
}