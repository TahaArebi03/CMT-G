<?php
// File: /app/Components/UserManagement/Models/User.php
require_once '../../../config/config.php';

class User {
    private $userId;
    private $name;
    private $email;
    private $password;    // مخزَّنة مشفّرة
    private $role;
    private $language;
    private $focus_mode;  // boolean

    public function __construct() {
        // فارغ: البيانات تُحمّل عبر setters أو من findBy…
    }

    // ——— Getters & Setters ———
    public function getUserId() { return $this->userId; }
    public function getName()   { return $this->name; }
    public function setName($n) { $this->name = $n; }

    public function getEmail()       { return $this->email; }
    public function setEmail($e)     { $this->email = $e; }

    public function getRole()        { return $this->role; }
    public function setRole($r)      { $this->role = $r; }

    public function getLanguage()    { return $this->language; }
    public function setLanguage($l)  { $this->language = $l; }

    public function isFocusMode()    { return $this->focus_mode; }
    public function setFocusMode($f){ $this->focus_mode = (bool)$f; }

    /**
     * يحفظ (Insert or Update) المستخدم في قاعدة البيانات.
     */
    public function save() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("DB connection failed: " . $conn->connect_error);
        }
        // تشفير كلمة المرور إذا كانت مُعيّنة وغير مُشفّرة
        if (!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }

        if ($this->userId) {
            // Update existing
            $stmt = $conn->prepare(
              "UPDATE users SET name=?, email=?, password=?, role=?, language=?, focus_mode=? 
               WHERE user_id=?"
            );
            $stmt->bind_param(
              "ssssisi",
              $this->name,
              $this->email,
              $this->password,
              $this->role,
              $this->language,
              $this->focus_mode,
              $this->userId
            );
        } else {
            // Insert new
            $stmt = $conn->prepare(
              "INSERT INTO users (name,email,password,role,language,focus_mode)
               VALUES (?,?,?,?,?,?)"
            );
            $stmt->bind_param(
              "sssssi",
              $this->name,
              $this->email,
              $this->password,
              $this->role,
              $this->language,
              $this->focus_mode
            );
        }
        $ok = $stmt->execute();
        if (!$this->userId) {
            $this->userId = $stmt->insert_id;
        }
        $stmt->close();
        $conn->close();
        return $ok;
    }

    /**
     * تسجيل الدخول: يتحقق من البريد وكلمة المرور.
     */
    public static function login($email, $password) {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    /**
     * استرجاع مستخدم بناءً على البريد الإلكتروني.
     */
    public static function findByEmail($email) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("DB connection failed: " . $conn->connect_error);
        }
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $u = new User();
            $u->userId     = $row['user_id'];
            $u->name       = $row['name'];
            $u->email      = $row['email'];
            $u->password   = $row['password'];
            $u->role       = $row['role'];
            $u->language   = $row['language'];
            $u->focus_mode = (bool)$row['focus_mode'];
            $stmt->close();
            $conn->close();
            return $u;
        }
        $stmt->close();
        $conn->close();
        return null;
    }

    /**
     * استرجاع مستخدم بناءً على المعرف.
     */
    public static function findById($id) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $u = new User();
            $u->userId     = $row['user_id'];
            $u->name       = $row['name'];
            $u->email      = $row['email'];
            $u->password   = $row['password'];
            $u->role       = $row['role'];
            $u->language   = $row['language'];
            $u->focus_mode = (bool)$row['focus_mode'];
            $stmt->close();
            $conn->close();
            return $u;
        }
        $stmt->close();
        $conn->close();
        return null;
    }

    /**
     * يعيد مصفوفة معلومات المستخدم.
     */
    public function getUserInfo() {
        return [
            'userId'     => $this->userId,
            'name'       => $this->name,
            'email'      => $this->email,
            'role'       => $this->role,
            'language'   => $this->language,
            'focus_mode' => $this->focus_mode
        ];
    }
}
