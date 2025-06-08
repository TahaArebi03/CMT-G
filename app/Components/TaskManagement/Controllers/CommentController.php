<?php
require '../../../../config/config.php';
require '../Models/Comment.php';
require '../Models/Task.php';
require '../../UserManagement/Models/User.php';
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
        $user_id = $_SESSION['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;
        if(!$task_id) {
            echo "لا يمكن عرض التعليقات لمهمة غير موجودة.";
            return;
        }
        
        // جلب التعليقات الخاصة بالمهمة
        $comments = Comment::getCommentsByTaskId($task_id);
        // عرض اسماء المستخدمين الذين قاموا بالتعليق
        $userNames = [];
        foreach ($comments as $comment) {
            $user = User::findById($comment->getUserId());
            $userNames[$comment->getCommentId()] = $user->getName();
        }

        include __DIR__ . '/../Views/commentsList.php';
    }

    // إنشاء تعليق جديد
    public function create()
    {
        $task_id = $_GET['task_id'] ?? 0;
        $user_id = $_SESSION['user_id'] ?? 0;
        $project_id = $_GET['project_id'] ?? 0;
        if(!$task_id) {
            echo "لا يمكن إضافة تعليق لمهمة غير موجودة.";
            return;
        }
        $task= Task::findById($task_id);
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $comment = new Comment();
            $comment->setTaskId($task_id);
            $comment->setUserId($user_id);
            $comment->setContent($_POST['content']);
            $comment->setCreatedAt(date('Y-m-d H:i:s'));
            if ($comment->save()) {
                header("Location: CommentController.php?action=list&task_id={$task_id}&project_id={$project_id}");
            } else {
                echo "حدث خطأ أثناء إضافة التعليق.";
            }
        }
        include __DIR__ . '/../Views/commentForm.php';
        
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
    