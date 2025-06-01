<?php
// Controllers/AuthController.php

require_once __DIR__ . '/../Models/Auth.php';

class AuthController {
    
    public function loginAction() {
        session_start();
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $user = Auth::login($email, $password);


            // التحقق من البريد الإلكتروني
            if (empty($user)) {
                $error = "البريد الإلكتروني غير صحيحة.";
            } elseif (!password_verify($password, $user['password'])) {
                $error = "كلمة المرور غير صحيحة.";
            } else {
                // تسجيل الدخول ناجح
                $_SESSION['user_id'] = $user['user_id'];

                // التحقق من نوع المستخدم وتوجيهه للواجهة المناسبة
                if ($user['role'] === 'Student') {
                    header('Location: ../../UserManagement/Views/userDashboard.php');
                } elseif ($user['role'] === 'Admin') {
                    header('Location: ../../ProjectManagement/Controllers/ProjectController.php?action=list');
                }
            }
        }

        include __DIR__ . '/../Views/login.php';
    }
    public function registerAction() {
        $error = "";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = $_POST['role'] ?? 'student';
            $language = $_POST['language'] ?? 'ar';
            $major = $_POST['major'] ?? '';

            // تحقق من صحة البيانات المدخلة
            if (empty($name) || empty($email) || empty($password)) {
                $error = "يرجى ملء جميع الحقول.";
            } elseif (Auth::emailExists($email)) {
                $error = "البريد الإلكتروني موجود بالفعل.";
            } else {
                // تسجيل المستخدم
            if (Auth::register($name, $email, $password, $role, $language, $major)) {
                
                if ($role === 'Student') {
                    
                    header('Location: ../../UserManagement/Controllers/UserController.php?action=dashboard');
                    exit;
                } elseif ($role === 'Admin') {
                    header('Location: ../../ProjectManagement/Controllers/ProjectController.php?action=list');
                    exit;
                }
            } else {
                $error = "فشل التسجيل. حاول مرة أخرى.";
            }
        }
        
    }
    include __DIR__ . '/../Views/register.php';
}   
    public function logoutAction() {
      
        session_start();
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
}
$controller = new AuthController();
$action = $_GET['action'] ?? 'login';
    switch ($action) {
        case 'login':
            $controller->loginAction();
            break;
        case 'register':
            $controller->registerAction();
            break;
        case 'logout':
            $controller->logoutAction();
            break;
        default:
            $controller->loginAction();
            break;
    }