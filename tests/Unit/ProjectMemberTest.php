<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/ProjectManagement/Models/ProjectMember.php';

class ProjectMemberTest extends TestCase
{
    public function testFindByProjectId()
    {
        $project_id = 73;
        $members = ProjectMember::findByProjectId($project_id);
        
        // تحقق من أن النتيجة ليست فارغة
        $this->assertNotEmpty($members, "Expected members to be found for project ID $project_id");
        
        // تحقق من أن كل عضو يحتوي على معرف المشروع الصحيح
        foreach ($members as $member) {
            $this->assertEquals($project_id, $member->getProjectId(), "Member project ID does not match expected project ID");  
        }
    }
}

?>