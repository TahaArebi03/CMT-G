<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/TaskManagement/Models/Task.php';

class TaskTest extends TestCase
{
    public function testSaveTaskInsert()
    {
        $task = new Task();
        $task->setProjectId(73);
        $task->setTitle('Test Task');
        $task->setDescription('This is a test task description.');
        $task->setAssignedTo(137);
        $task->setStatus('not_started');
        $task->setPriority('medium');
        $task->setDeadline('2023-12-31 23:59:59');

        // حفظ المهمة
        $result = $task->save();
        // تحقق من أن معرف المهمة تم تعيينه
        $this->assertNotNull($task->getTaskId(), "Expected task ID to be set after saving");
        // تحقق من أن العملية نجحت
        $this->assertTrue($result, "Task should be saved successfully");
    }
    public function testFindTasksByProjectId()
    {
        $project_id = 73;
        $tasks = Task::findByProjectId($project_id);

        // تحقق من أن النتيجة ليست فارغة
        $this->assertNotEmpty($tasks, "Expected tasks to be found for project ID $project_id");

        // تحقق من أن كل مهمة تحتوي على معرف المشروع الصحيح
        foreach ($tasks as $task) {
            $this->assertEquals($project_id, $task->getProjectId(), "Task project ID does not match expected project ID");
        }
    }
    public function testFindTaskById()
    {
        $task_id = 70; // تأكد من وجود مهمة بهذا المعرف في قاعدة البيانات
        $task = Task::findById($task_id);

        // تحقق من أن المهمة ليست فارغة
        $this->assertNotNull($task, "Expected task to be found for task ID $task_id");

        // تحقق من أن معرف المهمة هو نفسه المعرف المطلوب
        $this->assertEquals($task_id, $task->getTaskId(), "Task ID does not match expected task ID");
    }
    // public function testOrderTasksByPriority()
    // {
    //     $project_id = 73;
    //     $tasks = Task::findByProjectId($project_id);
    //     $ordered_tasks = Task::orderByPriority($tasks);

    //     // تحقق من أن المهام مرتبة حسب الأولوية
    //     $priorities = array_map(function($task) {
    //         return $task->getPriority();
    //     }, $ordered_tasks);

    //     // تحقق من أن الأولويات مرتبة بشكل صحيح
    //     $this->assertEquals(['high', 'medium', 'low'], $priorities, "Tasks are not ordered by priority correctly");
    // }
}
?>