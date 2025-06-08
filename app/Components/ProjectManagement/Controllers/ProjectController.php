<?php


require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/ProjectMember.php';
require_once __DIR__ . '/../../UserManagement/Models/User.php';

class ProjectController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * إظهار قائمة المشاريع
     */
    public function listAction()
    {
        // $project_id = intval($_GET['id'] ?? 0);
        if (isset($_SESSION['user_id'])) {

            $user_id = $_SESSION['user_id'];
            $user = User::findById($user_id);
            if ($user) {
                $project_id = $user->getProjectId();
                if ($project_id) {
                    $project = Project::findById($project_id);
                } else {
                    $project = null;
                }
            } else {
                $project = null;
            }
        } else {
            $project = null;
        }
        include __DIR__ . '/../Views/projectList.php';
        exit; 
    }

    /**
     * إظهار نموذج إنشاء مشروع أو معالجة الإرسال
     */
    public function createAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title'       => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'objectives'  => trim($_POST['objectives'] ?? ''),
                'deadline'    => trim($_POST['deadline'] ?? date('Y-m-d H:i:s')),
                'status'      => trim($_POST['status'] ?? 'active')
            ];

            $project = Project::createProject($data); 
            if ($project) {
                
                header('Location: ProjectController.php?action=list&id='. $project->getId());
                exit;
            } else {
                echo "Failed to create project";
            }
          
        } else {
            include __DIR__ . '/../Views/projectForm.php';
        }
    }

    /**
     * إظهار تفاصيل مشروع
     */
    public function viewAction()
    {
        //id -> projectList.php
        $project_id = intval($_GET['id'] ?? 0);
        if (!$project_id) {
            header('Location: ProjectController.php?action=list');
            exit;
        }
        $project = Project::findById($project_id);
        $user= User::findById($_SESSION['user_id'] ?? 0);
        include __DIR__ . '/../Views/projectDetails.php';

    }

    /**
     * إظهار نموذج التعديل أو معالجة التحديث
     */
    // public function editAction()
    // {
    //     $id = intval($_GET['id'] ?? 0);
    //     if (!$id) {
    //         header('Location: ProjectController.php?action=list');
    //         exit;
    //     }
    //     $project = Project::findById($id);

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $project->setTitle(trim($_POST['title'] ?? $project->getTitle()));
    //         $project->setDescription(trim($_POST['description'] ?? $project->getDescription()));
    //         $project->setObjectives(trim($_POST['objectives'] ?? $project->getObjectives()));
    //         $project->setDeadline(trim($_POST['deadline'] ?? $project->getDeadline()));
    //         $project->setStatus(trim($_POST['status'] ?? $project->getStatus()));

    //         $project->updateProject();
    //         header("Location: ProjectController.php?action=view&id={$id}");
    //         exit;
    //     } else {
    //         include __DIR__ . '/../Views/projectForm.html';
    //     }
    // }

    /**
     * أرشفة المشروع
     */
    // public function archiveAction()
    // {
    //     $id = intval($_GET['id'] ?? 0);
    //     if ($id) {
    //         $project = Project::findById($id);
    //         $project->archiveProject();
    //     }
    //     header('Location: ProjectController.php?action=list');
    //     exit;
    // }
}

/**
 * بسيط لتوزيع الـ actions
 */
$controller = new ProjectController();
$action     = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        $controller->createAction();
        break;
    case 'view':
        $controller->viewAction();
        break;
    // case 'edit':
    //     $controller->editAction();
    //     break;
    // case 'archive':
    //     $controller->archiveAction();
    //     break;
    case 'list':
    default:
        $controller->listAction();
        break;
}
