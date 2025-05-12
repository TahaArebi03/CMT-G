<?php
require_once __DIR__ . '../../../../../config/config.php';

class Auth {

    public static function login($email, $password) {
        $db = new Connect();
        $pdo = $db->conn;
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }
    public static function register($name, $email, $password, $role, $language,$major) {
        $db = new Connect();
        $pdo = $db->conn;

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // إدخال المستخدم في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, language, major) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hashedPassword, $role, $language, $major]);
    }
}
?>