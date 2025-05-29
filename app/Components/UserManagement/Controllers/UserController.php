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

    /**
     * Show login form or process login submission.
     */
//     public function loginAction()
//     {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $email    = trim($_POST['email'] ?? '');
//             $password = trim($_POST['password'] ?? '');

//             // Attempt to authenticate
//             $user = User::login($email, $password);
//             if ($user) {

//                 $_SESSION['user'] = $user;  // ← ضروري جدًا

//                 if($user->getRole()=='student'){
//                     header('Location: ../Views/userDashboard');
//                 }
//                 elseif($user->getRole()=='admin'){
//                     header('Location: ../../ProjectManagnemt/Views/projectList.php');
//                 }
                
//                 exit;
//             } else {
//                 $error = 'Invalid email or password.';
//                 include __DIR__ . '/../Views/login.html';
//             }
//         } else {
//             include __DIR__ . '/../Views/login.html';
//         }
//     }

//     /**
//      * Show registration form or process registration submission.
//      */
//     public function registerAction()
//     {
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             $name     = trim($_POST['name'] ?? '');
//             $email    = trim($_POST['email'] ?? '');
//             $major    = trim($_POST['major'] ?? '');
//             $password = trim($_POST['password'] ?? '');
//             $role = trim($_POST['role'] ?? '');
//             $language = trim($_POST['language'] ?? '');
//             // Instantiate and populate User
//             $user = new User();
//             $user->setName($name);
//             $user->setEmail($email);  
//             $user->setMajor($major);    
//             $user->setPassword($password);
//             $user->setRole($role);
//             $user->setLanguage($language);
        
//       // plaintext; save() will hash
//             // defaults: language = 'en', focus_mode = false
//             if($user->save()){
//                 $_SESSION['user'] = $user;
//                 if($user->getRole()=='student'){

//                     header('Location: ../Views/userDashboard.php');
//                     exit();
//                 }
//                 if($user->getRole()=='admin'){
                    
//                     header('Location: ../../ProjectManagement/Views/projectList.php');
//                     exit();
    
//                 }
//             }
           

//     } else {
//         // عرض نموذج التسجيل
//         include __DIR__ . '/../Views/register.html';
//     }
// }

//     /**
//      * Log the user out.
//      */
//     public function logoutAction()
//     {
//         session_unset();
//         session_destroy();
//         header('Location: login.php');
//         exit;
//     }

    // /**
    //  * Show the user's dashboard.
    //  */
    public function dashboardAction()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: register.php');
            exit;
        }
        $user = User::findById($_SESSION['user_id']);
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
    
    // case 'login':
    //     $controller->loginAction();
    //     break;
    // case 'register':
    //     $controller->registerAction();
    //     break;
    // case 'logout':
    //     $controller->logoutAction();
    //     break;
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
