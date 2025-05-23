<?php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../../UserManagement/Models/StudentUser.php';

class TaskController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * عرض قائمة المهام لمشروع معين
     */
    public function listAction()
    {
        $project_id = intval($_GET['project_id'] ?? 0);
        if (!$project_id) {
            // إذا لم يُحدد مشروع، نعيد إلى قائمة المشاريع
            header('Location: ../../ProjectManagement/Controllers/ProjectController.php?action=list');
            exit;
        }

        // جلب كل المهام للمشروع وفرزها حسب الأولوية
        $tasks  = Task::orderByPriority(Task::findByProjectId($project_id));
        

        include __DIR__ . '/../Views/taskList.php';
    }

    /**
     * إنشاء مهمة جديدة (GET يعرض النموذج، POST يعالج الإرسال)
     */
    public function createAction()
    {
        $project_id = intval($_GET['project_id'] ?? 0);
        if (!$project_id) {
            header('Location: ../../ProjectManagement/Controllers/ProjectController.php?action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $t = new Task();
            $t->setProjectId($project_id);
            $t->setTitle(trim($_POST['title'] ?? ''));
            $t->setDescription(trim($_POST['description'] ?? ''));
            $t->setAssignedTo(intval($_POST['assigned_to'] ?? 0) ?: null);
            $t->setStatus(trim($_POST['status'] ?? 'not_started'));
            $t->setPriority(trim($_POST['priority'] ?? 'medium'));
            $t->setDeadline(trim($_POST['deadline'] ?? date('Y-m-d H:i:s')));
            $t->save();
            
            header("Location: TaskController.php?action=list&project_id={$project_id}");
            exit;
        } else {
            // جلب قائمة الطلاب للاختيار من الفورم
            $students  = StudentUser::findByProject($project_id);
            include __DIR__ . '/../Views/taskForm.php';
        }
    }

    /**
     * تعديل مهمة (GET يعرض النموذج المُعبَّأ، POST يعالج التحديث)
     */
    public function editAction()
    {
        $taskId = intval($_GET['id'] ?? 0);
        if (!$taskId) {
            header('Location: TaskController.php?action=list');
            exit;
        }

        $task = Task::findById($taskId);
        if (!$task) {
            header('Location: TaskController.php?action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task->setTitle(trim($_POST['title']     ?? $task->getTitle()));
            $task->setDescription(trim($_POST['description'] ?? $task->getDescription()));
            $task->setAssignedTo(intval($_POST['assigned_to'] ?? $task->getAssignedTo()) ?: null);
            $task->setStatus(trim($_POST['status']   ?? $task->getStatus()));
            $task->setPriority(trim($_POST['priority'] ?? $task->getPriority()));
            $task->setDeadline(trim($_POST['deadline'] ?? $task->getDeadline()));
            $task->save();

            header("Location: TaskController.php?action=list&project_id={$task->getProjectId()}");
            exit;
        } else {
            // جلب قائمة الطلاب للفورم
            $students = StudentUser::findAllStudents();
            include __DIR__ . '/../Views/taskForm.php';
        }
    }

    /**
     * حذف مهمة
     */
    public function deleteAction()
    {
        $taskId = intval($_GET['id'] ?? 0);
        if ($taskId) {
            $task = Task::findById($taskId);
            if ($task) {
                $projectId = $task->getProjectId();

                // تنفيذ الحذف
                $db  = new Connect();
                $pdo = $db->conn;
                $stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = ?");
                $stmt->execute([$taskId]);

                header("Location: TaskController.php?action=list&project_id={$projectId}");
                exit;
            }
        }
        // إذا لم ينجح الحذف، نعيد للقائمة العامة
        header('Location: TaskController.php?action=list');
        exit;
    }
}

// Router-like dispatch
$controller = new TaskController();
$action     = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        $controller->createAction();
        break;
    case 'edit':
        $controller->editAction();
        break;
    case 'delete':
        $controller->deleteAction();
        break;
    case 'list':
    default:
        $controller->listAction();
        break;
}
