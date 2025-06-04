<?php


require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/ProjectMember.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '../../../UserManagement/Models/StudentUser.php';


class ProjectMemberController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * عرض أعضاء مشروع محدد
     */
    public function listAction()
    {
        $project_id = intval($_GET['project_id'] ?? 0);
        if (empty($project_id)) {
            header('Location: ProjectController.php?action=list');
            exit;
        }
        $user=User::findById($_SESSION['user_id'] ?? 0);
        $members = ProjectMember::findByProjectId($project_id);
        include __DIR__ . '/../Views/viewMembers.php';

    }

    /**
     * إضافة عضو جديد إلى المشروع
     */
    public function addAction(){
    // فقط الطلاب الذين ليس لهم مشروع مرتبط
    $students = StudentUser::findAllStudents();
    $project_id = intval($_GET['project_id'] ?? 0);
    
    if (!$project_id) {
        header('Location: ProjectController.php?action=list');
        exit;
    }

    include __DIR__ . '/../Views/addMemberForm.php';
}
    
    
        /**
        * حفظ عضو جديد في المشروع
        */
        public function saveAction()
        {
            $project_id = intval($_GET['project_id']);
            $user_id = intval($_POST['user_id']);
        
            if ($project_id && $user_id) {
                $db = new Connect();
                $pdo = $db->conn;
                
                // تحديث المشروع للطالب
                $stmt = $pdo->prepare("UPDATE users SET project_id = ? WHERE user_id = ?");
                $stmt->execute([$project_id, $user_id]);

            }

            header("Location: ProjectMemberController.php?action=list&project_id=$project_id");
            exit;
        }



    /**
     * تعديل دور عضو في المشروع
     */
    public function editAction()
    {
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $member = new ProjectMember();
        //     $member->setProjectId(intval($_POST['project_id']));
        //     $member->setUserId(intval($_POST['user_id']));
        //     $member->updateRole(trim($_POST['role_in_project']));
        //     header("Location: ProjectMemberController.php?action=list&project_id={$member->getProjectId()}");
        //     exit;
        // } else {

        //     include __DIR__ . '/../Views/addMemberForm.html';
        // }
    }
}


$pmController = new ProjectMemberController();
$action       = $_GET['action'] ?? 'list';

switch ($action) {
    case 'add':
        $pmController->addAction();
        break;
    case 'edit':
        $pmController->editAction();
        break;
    case 'save':
        $pmController->saveAction();
        break;
    case 'list':
    default:
        $pmController->listAction();
        break;
}
