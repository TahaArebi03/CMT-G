<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/UserManagement/Models/StudentUser.php';

class StudentTest extends TestCase
{
    public function testFindAllStudents()
    {
        $students = StudentUser::findAllStudents();
        $this->assertIsArray($students);
        $this->assertNotEmpty($students);
        foreach ($students as $student) {
            $this->assertInstanceOf(StudentUser::class, $student);
            $this->assertNotEmpty($student->getName());
            $this->assertNotEmpty($student->getEmail());
        }
    }
} 


?>