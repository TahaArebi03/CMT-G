<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/ProjectManagement/Models/Project.php';

class ProjectTest extends TestCase
{

    public function testCreateProject()
    {
        $_SESSION = ['user_id' => 79];

        $project = new Project();
        $project->setTitle("Test Project");
        $project->setDescription("This is a test project.");
        $project->setObjectives("Objective 1, Objective 2");
        $project->setDeadline('2024-12-31 23:59:59');
        $project->setStatus('active');

        $this->assertTrue($project->save(), "Failed to save the project.");
        $this->assertNotEmpty($project->getId(), "Project ID should not be empty after saving.");
    }

    public function testFindProjectById()
    {
        $project = Project::findById(73); // Assuming project with ID 73 exists
        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals(73, $project->getId());
        $this->assertNotEmpty($project->getTitle());
    }
}
?>