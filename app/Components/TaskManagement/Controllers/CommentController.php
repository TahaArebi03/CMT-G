<?php
require '../../../../config/config.php';
require '../Models/Comment.php';

class CommentController
{

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

    }

    // عرض التعليقات الخاصة بمهمة معينة
    public function list()
    {
        $task_id = $_GET['task_id'] ?? 0;
        $user_id = $_GET['user_id'] ?? 0;
        if(!$task_id) {
            echo "لا يمكن عرض التعليقات لمهمة غير موجودة.";
            return;
        }
        // جلب التعليقات الخاصة بالمهمة
        $comments = Comment::getCommentsByTaskId($task_id);
        require_once __DIR__ . '/../Views/comments/commentsList.php';
    }

    // إنشاء تعليق جديد
    public function create()
    {
        $task_id = $_GET['task_id'] ?? 0;
        if(!$task_id) {
            echo "لا يمكن إضافة تعليق لمهمة غير موجودة.";
            return;
        }
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $comment = new Comment();
            $comment->setTaskId($task_id);
            $comment->setUserId($_POST['user_id']);
            $comment->setCommentText($_POST['content']);
            $comment->setCreatedAt(date('Y-m-d H:i:s'));
            if ($comment->save()) {
                header("Location: CommentController.php?action=list&task_id={$task_id}");
            } else {
                echo "حدث خطأ أثناء إضافة التعليق.";
            }
        }
        include __DIR__ . '/../Views/commentsForm.php';
    }

    // تحديث تعليق موجود
    // public function update($data)
    // {
    //     $this->model->comment_id = $data['comment_id'];
    //     $this->model->content = $data['content'];
    //     if ($this->model->update()) {
    //         echo "تم تعديل التعليق بنجاح.";
    //     } else {
    //         echo "حدث خطأ أثناء تعديل التعليق.";
    //     }
    // }

    // // حذف تعليق
    // public function destroy($comment_id)
    // {
    //     if ($this->model->delete($comment_id)) {
    //         echo "تم حذف التعليق بنجاح.";
    //     } else {
    //         echo "حدث خطأ أثناء حذف التعليق.";
    //     }
    // }
}
$controller = new CommentController();
$action = $_GET['action'] ?? 'list';
switch ($action) {
    case 'list':
        $controller->list();
        break;
    case 'create':
        $controller->create();
        break;
    // case 'update':
    //     $controller->update($_POST);
    //     break;
    // case 'destroy':
    //     $controller->destroy($_GET['comment_id']);
    //     break;
    default:
        echo "Action not found.";
}
    