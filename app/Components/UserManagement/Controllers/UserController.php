<?php

require_once '../../../../config/config.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/StudentUser.php';
require_once __DIR__ . '/../Models/AdminUser.php';

class UserController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // /**
    //  * Show the user's dashboard.
    //  */
    public function dashboardAction()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ../../Auth/Controllers/AuthController.php?action=login');
            exit;
        }
        $user = User::findById($_SESSION['user_id']);
        if (!$user) {
            echo 'User not found.';
            var_dump($user);
            exit;
        }
        
        include __DIR__ . '/../Views/userDashboard.php';
    }

    /**
     * Show or process the profile page.
     */
    public function profileAction()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: register.php');
            exit;
        }
        $user = User::findById($_SESSION['user']->getUserId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update user properties
            $user->setName(trim($_POST['name'] ?? $user->getName()));
            $user->setEmail(trim($_POST['email'] ?? $user->getEmail()));
            if (!empty($_POST['password'])) {
                $user->setPassword(trim($_POST['password'])); // save() will hash
            }
            $user->setLanguage($_POST['language'] ?? $user->getLanguage());


            if ($user->save()) {
                $success = 'Profile updated successfully.';
            } else {
                $error = 'Failed to update profile.';
            }
        }

        include __DIR__ . '/../Views/profile.html';
    }
}

// Router-like dispatch (simple)
$controller = new UserController();
$action     = $_GET['action'] ?? 'dashboard';
switch ($action) {
    case 'dashboard':
        $controller->dashboardAction();
        break;
    case 'profile':
        $controller->profileAction();
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Page not found';
        break;
}
