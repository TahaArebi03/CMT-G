<?php
// File: /app/Components/ProjectManagement/Controllers/ProjectMemberController.php

require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../Models/ProjectMember.php';
require_once __DIR__ . '/../Models/Project.php';

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
        $projectId = intval($_GET['project_id'] ?? 0);
        if (!$projectId) {
            header('Location: ProjectController.php?action=list');
            exit;
        }
        $members = ProjectMember::findByProjectId($projectId);
        include __DIR__ . '/../Views/memberList.html';
    }

    /**
     * إضافة عضو جديد إلى المشروع
     */
    public function addAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member = new ProjectMember();
            $member->setProjectId(intval($_POST['project_id']));
            $member->setUserId(intval($_POST['user_id']));
            $member->setRoleInProject(trim($_POST['role_in_project'] ?? 'member'));
            $member->save();
            header("Location: ProjectMemberController.php?action=list&project_id={$member->getProjectId()}");
            exit;
        } else {
            include __DIR__ . '/../Views/memberForm.html';
        }
    }

    /**
     * تعديل دور عضو في المشروع
     */
    public function editAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $member = new ProjectMember();
            $member->setProjectId(intval($_POST['project_id']));
            $member->setUserId(intval($_POST['user_id']));
            $member->updateRole(trim($_POST['role_in_project']));
            header("Location: ProjectMemberController.php?action=list&project_id={$member->getProjectId()}");
            exit;
        } else {
            // نموذج التعديل يمكن أن يكون نفسه memberForm.html مع حقول معبأة
            include __DIR__ . '/../Views/memberForm.html';
        }
    }
}

/**
 * Router-like dispatch
 */
$pmController = new ProjectMemberController();
$action       = $_GET['action'] ?? 'list';

switch ($action) {
    case 'add':
        $pmController->addAction();
        break;
    case 'edit':
        $pmController->editAction();
        break;
    case 'list':
    default:
        $pmController->listAction();
        break;
}
