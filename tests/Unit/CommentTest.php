<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/TaskManagement/Models/Comment.php';

class CommentTest extends TestCase
{
    public function testSaveNewComment()
    {
        

        // إعداد بيانات التعليق
        $comment = new Comment();
        $comment->setTaskId(90); 
        $comment->setUserId(137);  
        $comment->setContent("Test comment.");
        $comment->setCreatedAt(date('Y-m-d H:i:s'));

        // حفظ التعليق
        $result = $comment->save();

        // التحقق من نجاح الحفظ
        $this->assertTrue($result, "Failed to save comment.");
        $this->assertNotEmpty($comment->getCommentId(), "The comment_id must not be empty after saving.");
    }
    public function testGetCommentsByTaskId()
{
    $task_id = 41;

    $comments = Comment::getCommentsByTaskId($task_id);

    // التحقق أن النتيجة مصفوفة
    $this->assertIsArray($comments, "The result should be an array.");

    // إذا فيه تعليقات، نتحقق من النوع
    if (!empty($comments)) {
        foreach ($comments as $comment) {
            $this->assertInstanceOf(Comment::class, $comment, "All elements must be of type Comment.");
            $this->assertEquals($task_id, $comment->getTaskId(), "Task ID does not match the entry.");
        }
    }     
}
}

?>