<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/UserManagement/Models/User.php';

// require_once __DIR__ . '/../../vendor/autoload.php';

class UserTest extends TestCase
{
    public function testSetPassword()
    {
        $user = new User();
        $plainPassword = 'testPassword123';
        $user->setPassword($plainPassword);
        $hashedPassword = $user->getPassword();
        
        // التأكد من أن كلمة المرور مشفرة
        $this->assertNotEquals($plainPassword, $hashedPassword);
        // التأكد من أن كلمة المرور يمكن التحقق منها
        $this->assertTrue(password_verify($plainPassword, $hashedPassword));

    }
    public function testGetUserInfo(){
        $user = new User();
        $user->setUserId(1);
        $user->setName('Test User');
        $user->setEmail('test@example.com');
        $user->setPassword('testPassword123'); // سيتم تشفيرها
        $user->setMajor('Computer Science');
        $user->setRole('admin');
        $user->setLanguage('ar');
        $user->setProjectId(1001);

        $info=$user->getUserInfo();
        $this->assertArrayHasKey('user_id', $info);
        $this->assertEquals("Test User", $info['name']);
        $this->assertEquals("test@example.com", $info['email']);
        $this->assertEquals("admin", $info['role']);
        $this->assertEquals("ar", $info['language']);
        $this->assertEquals("Computer Science", $info['major']);
        $this->assertEquals(1001, $info['project_id']);
    }
    public function testSaveUser(){
        $user=new User();
        $user->setUserId(1);
        $user->setName('Test User');
        $user->setEmail('test@example.com');
        $user->setPassword('testPassword123'); // سيتم تشفيرها
        $user->setMajor('Computer Science');
        $user->setRole('admin');
        $user->setLanguage('ar');
        $user->setProjectId(1001);

        $this->assertTrue($user->save());

    }
    public function testFindUserById(){
    $user = new User();
    $email = 'finduser_' . uniqid() . '@example.com';
    $user->setName('Test Find ID');
    $user->setEmail($email);
    $user->setPassword('findid123');
    $user->setMajor('Engineering');
    $user->setRole('Student');
    $user->setLanguage('en');
    $user->setProjectId(null);
    $user->save(); // هذا يحفظ المستخدم ويضيف userId تلقائيًا
    
    $id = $user->getUserId(); // خزن الـ ID الجديد

    $this->assertTrue($user->save(), 'User should be saved successfully');

    $found = User::findById($id);
    $this->assertInstanceOf(User::class, $found);
    $this->assertEquals('Test Find ID', $found->getName());
    $this->assertEquals($email, $found->getEmail());

    }  
    public function testFindUserByEmail(){
    $user = new User();
    $email = 'findemail_' . uniqid() . '@example.com';
    $user->setName('Test Find Email');
    $user->setEmail($email);
    $user->setPassword('findemail123');
    $user->setMajor('Mathematics');
    $user->setRole('Student');
    $user->setLanguage('en');
    $user->setProjectId(null);
    $user->save();

    $found = User::findByEmail($email);
    $this->assertInstanceOf(User::class, $found);
    $this->assertEquals('Test Find Email', $found->getName());
    $this->assertEquals($email, $found->getEmail());

    }  
}


?>