<?php
require_once __DIR__ . '/../../../../config/config.php';
class Comment{
    private $comment_id;
    private $task_id;
    private $user_id;
    private $content;
    private $created_at;
    // public function __construct(){

    // }   
    // ——— Getters & Setters ———
    
    public function getCommentId(){return $this->comment_id;}

    public function getTaskId(){return $this->task_id;}

    public function getUserId(){return $this->user_id;}

    public function getContent(){return $this->content;}

    public function getCreatedAt(){return $this->created_at;}
    public function setCommentId($id){$this->comment_id=$id;}
    public function setTaskId($id){$this->task_id=$id;}
    public function setUserId($id){$this->user_id=$id;}
    public function setContent($text){$this->content=$text;}
    public function setCreatedAt($date){$this->created_at=$date;}

    // ادراج وتحديث
    public function save(): bool
    {
        $db  = new Connect();
        $pdo = $db->conn;
        echo "Task ID: " . $this->task_id . "<br>";
        echo "User ID: " . $this->user_id . "<br>";
        echo "Content: " . $this->content . "<br>";
        echo "Created At: " . $this->created_at . "<br>";


        if ($this->comment_id) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE comments
               SET task_id = ?, user_id = ?, content = ?, created_at = ?
               WHERE comment_id = ?"
            );
            return $stmt->execute([
                $this->task_id,
                $this->user_id,
                $this->content,
                $this->created_at,
                $this->comment_id
            ]);
        } else {
            // إدراج جديد
            $stmt = $pdo->prepare(
              "INSERT INTO comments (task_id, user_id, content, created_at)
               VALUES (?, ?, ?, ?)"
            );
            $ok= $stmt->execute([
                $this->task_id,
                $this->user_id,
                $this->content,
                $this->created_at
            ]);
            if($ok){
                $this->comment_id = $pdo->lastInsertId();
            }   
            return $ok;
        }
}
    // جلب جميع التعليقات في المهمة
    public static function getCommentsByTaskId($task_id){
        try{
        $db = new Connect();
        $pdo = $db->conn;
        $stmt= $pdo->prepare("SELECT * FROM comments WHERE task_id=?");
        $stmt->execute([$task_id]);
        $comments = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $comment = new Comment();
            $comment->setCommentId($row['comment_id']);
            $comment->setTaskId($row['task_id']);
            $comment->setUserId($row['user_id']);
            $comment->setContent($row['content']);
            $comment->setCreatedAt($row['created_at']);
            $comments[] = $comment;
        }
        return $comments;
    }catch(Exception $e){
        error_log("Error fetching comments: " . $e->getMessage());
        return [];
      }
    }

    // حذف تعليق
    // public function delete($comment_id){
    //     $db = new Connect();
    //     $pdo = $db->conn;
    //     $stmt= $pdo->prepare("DELETE FROM comments WHERE comment_id=?");
    //     return $stmt->execute([$comment_id]);

    // }
}