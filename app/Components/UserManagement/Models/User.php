<?php
require_once __DIR__ . '/../../../../config/config.php';

class User {
    private $userId;
    private $name;
    private $email;
    private $password; // مشفرة
    private $role;
    private $language;
    private $major;
    private $projectId;

    public function __construct() {}

    // ——— Getters & Setters ———

    public function getUserId()   { return $this->userId; }
    public function setUserId($id) { $this->userId = $id; }
    public function getPassword() { return $this->password; }
    public function getName()     { return $this->name; }
    public function setName($n)   { $this->name = $n; }

    public function getEmail()    { return $this->email; }
    public function setEmail($e)  { $this->email = $e; }

    public function getRole()     { return $this->role; }
    public function setRole($r)   { $this->role = $r; }

    public function getLanguage()     { return $this->language; }
    public function setLanguage($l)   { $this->language = $l; }

    public function getMajor()       { return $this->major; }
    public function setMajor($m)     { $this->major = $m; }

    public function getProjectId()   { return $this->projectId; }
    public function setProjectId($p) { $this->projectId = $p; }

    /**
     * تشفير كلمة المرور
     */
    public function setPassword(string $plainPassword): void {
        $this->password = password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * حفظ المستخدم (تسجيل أو تحديث)
     */
    public function save(): bool {
        $db  = new Connect();
        $pdo = $db->conn;

        if ($this->userId) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE users
               SET name = ?, email = ?, password = ?, role = ?, language = ?, project_id = ?
               WHERE user_id = ?"
            );
            return $stmt->execute([
                $this->name,
                $this->email,
                $this->password,
                $this->role,
                $this->language,
                $this->projectId,
                $this->userId
            ]);
        } else {
            // إدخال جديد
            $stmt = $pdo->prepare(
              "INSERT INTO users (name, email, password, role, language, project_id)
               VALUES (?, ?, ?, ?, ?, ?)"
            );
            $ok = $stmt->execute([
                $this->name,
                $this->email,
                $this->password,
                $this->role,
                $this->language,
                $this->projectId
            ]);
            if ($ok) {
                $this->userId = $pdo->lastInsertId();
            }
            return $ok;
        }
    }

    /**
     * تسجيل الدخول
     */
    public static function login(string $email, string $password): ?User {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public static function findByEmail(string $email): ?User {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $u = new User();
        $u->userId     = $row['user_id'];
        $u->name       = $row['name'];
        $u->email      = $row['email'];
        $u->password   = $row['password'];
        $u->role       = $row['role'];
        $u->language   = $row['language'];
        $u->projectId  = $row['project_id'];
        return $u;
    }

    public static function findById(int $id): ?User {
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $u = new User();
        $u->userId     = $row['user_id'];
        $u->name       = $row['name'];
        $u->email      = $row['email'];
        $u->password   = $row['password'];
        $u->role       = $row['role'];
        $u->language   = $row['language'];
        $u->projectId  = $row['project_id']; 
        return $u;
    }

    public function getUserInfo(): array {
        return [
            'user_id'    => $this->userId,
            'name'       => $this->name,
            'email'      => $this->email,
            'role'       => $this->role,
            'language'   => $this->language,
            'project_id' => $this->projectId
        ];
    }
}
