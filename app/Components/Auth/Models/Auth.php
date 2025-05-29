<?php
require_once __DIR__ . '../../../../../config/config.php';

class Auth {

    public static function login($email, $password) {
        $db = new Connect();
        $pdo = $db->conn;
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user;
        }
        
        return null;
    }
    public static function emailExists($email) {
        $db = new Connect();
        $pdo = $db->conn;
        try {
            // تحقق مما إذا كان البريد الإلكتروني موجودًا بالفعل
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
        } catch (PDOException $e) {
            // في حالة حدوث خطأ في قاعدة البيانات
            return null;
        }
        
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function register($name, $email, $password, $role, $language,$major) {
        $db = new Connect();
        $pdo = $db->conn;

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        // تحقق مما إذا كان البريد الإلكتروني موجودًا بالفعل
        if (self::emailExists($email)==null) {
            // إدخال المستخدم في قاعدة البيانات
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, language, major) VALUES (?, ?, ?, ?, ?, ?)");
           $ok=$stmt->execute([$name, $email, $hashedPassword, $role, $language, $major]);

           if ($ok) {
               // تعيين معرف المستخدم
               $user_id = $pdo->lastInsertId();
               // تعيين معرف المستخدم في الجلسة
               session_start();
               $_SESSION['user_id'] = $user_id;
           }
           return $ok;
        } else {

            // إذا كان البريد الإلكتروني موجودًا
            return false;
        }
    }
}
?>