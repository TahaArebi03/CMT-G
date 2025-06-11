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

    public function getMajor()       { return $this->major; }
    public function setMajor($m)     { $this->major = $m; }

    public function getLanguage()     { return $this->language; }
    public function setLanguage($l)   { $this->language = $l; }

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
        try{
        $db  = new Connect();
        $pdo = $db->conn;

        if ($this->userId) {
            // تحديث
            $stmt = $pdo->prepare(
              "UPDATE users
               SET name = ?, email = ?, password = ?, major = ?, role = ?, language = ?, project_id = ?
               WHERE user_id = ?"
            );
            return $stmt->execute([
                $this->name,
                $this->email,
                $this->password,
                $this->major,
                $this->role,
                $this->language,
                $this->projectId,
                $this->userId
            ]);
        } else {
            // إدخال جديد
            $stmt = $pdo->prepare(
              "INSERT INTO users (name, email, password, major, role, language, project_id)
               VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $ok = $stmt->execute([
                $this->name,
                $this->email,
                $this->password,
                $this->major,
                $this->role,
                $this->language,
                $this->projectId
            ]);
            if ($ok) {
                $this->userId =$pdo->lastInsertId();
                

            }
            return $ok;
        }
    } catch (PDOException $e) {
            // في حالة حدوث خطأ في قاعدة البيانات
            error_log("Database Error in save(): " . $e->getMessage());
            return false;
        }
    }

    public static function findByEmail(string $email): ?User {
        try{
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
    } catch (PDOException $e){
        error_log("FindByEmail Error: " . $e->getMessage());
        return null;
    }
    }
    public static function findById(int $id): ?User {
        try{
        $db  = new Connect();
        $pdo = $db->conn;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $u = new User();
        $u->setUserId($row['user_id']);
        $u->setName($row['name']);
        $u->setEmail($row['email']);
        $u->password = $row['password']; //هيا مشفرة مش ضروري نشفرها تاني
        $u->setRole($row['role']);
        $u->setLanguage($row['language']);
        $u->setMajor($row['major']);
        $u->setProjectId($row['project_id']);
        return $u;
        } catch (PDOException $e){
            error_log("FindById Error: " . $e->getMessage());
        return null;
        }
    }

    public function getUserInfo(): array {
        return [
            'user_id'    => $this->getUserId(),
            'name'       => $this->getName(),
            'email'      => $this->getEmail(),
            'major'      => $this->getMajor(),
            'role'       => $this->getRole(),
            'language'   => $this->getLanguage(),
            'project_id' => $this->getProjectId()
        ];
    }
}
