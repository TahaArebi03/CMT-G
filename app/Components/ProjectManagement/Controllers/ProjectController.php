<?php
// File: /app/Components/ProjectManagement/Controllers/ProjectController.php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/ProjectMember.php';

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
        $db    = new Connect();
        $pdo   = $db->conn;
        $stmt  = $pdo->query("SELECT * FROM projects ORDER BY project_id DESC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // عرض الـ view
        include __DIR__ . '/../Views/projectList.html';
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
                'status'      => trim($_POST['status'] ?? 'active'),
                'created_by'  => $_SESSION['user_id'] ?? null
            ];
            $project = Project::createProject($data);
            header('Location: ProjectController.php?action=list');
            exit;
        } else {
            include __DIR__ . '/../Views/projectForm.html';
        }
    }

    /**
     * إظهار تفاصيل مشروع
     */
    public function viewAction()
    {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: ProjectController.php?action=list');
            exit;
        }
        $project = Project::findById($id);
        include __DIR__ . '/../Views/projectDetails.html';
    }

    /**
     * إظهار نموذج التعديل أو معالجة التحديث
     */
    public function editAction()
    {
        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: ProjectController.php?action=list');
            exit;
        }
        $project = Project::findById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $project->setTitle(trim($_POST['title'] ?? $project->getTitle()));
            $project->setDescription(trim($_POST['description'] ?? $project->getDescription()));
            $project->setObjectives(trim($_POST['objectives'] ?? $project->getObjectives()));
            $project->setDeadline(trim($_POST['deadline'] ?? $project->getDeadline()));
            $project->setStatus(trim($_POST['status'] ?? $project->getStatus()));

            $project->updateProject();
            header("Location: ProjectController.php?action=view&id={$id}");
            exit;
        } else {
            include __DIR__ . '/../Views/projectForm.html';
        }
    }

    /**
     * أرشفة المشروع
     */
    public function archiveAction()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            $project = Project::findById($id);
            $project->archiveProject();
        }
        header('Location: ProjectController.php?action=list');
        exit;
    }
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
    case 'edit':
        $controller->editAction();
        break;
    case 'archive':
        $controller->archiveAction();
        break;
    case 'list':
    default:
        $controller->listAction();
        break;
}
