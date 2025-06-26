<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/Voting/Models/Vote.php';
class VoteTest extends TestCase
{
    public function testCreateVote()
 {
    $vote = new Vote();
    $vote->setProjectId(73);         
    $vote->setQuestion("do you want");
    $vote->setStatus("open");
    $vote->setCreatedBy(137);        

    $result = $vote->createVote();
        
    $this->assertTrue($result, "Failed to create vote");
    $this->assertNotEmpty($vote->getVoteId(), "Voting ID not set after creation");
  }

  public function testGetVoteById()
 {
    $vote = new Vote();
    $vote->setVoteId(103);
    $result = $vote->getVoteById(103);
    $this->assertNotEmpty($result, "Failed to retrieve vote by ID");
 }

    public function testGetVotesByProjectId()
 {
    $vote = new Vote();
    $result = $vote->getAllVotesByProject(73);
    $this->assertIsArray($result, "Failed to retrieve votes by project ID");
    $this->assertNotEmpty($result, "No votes found for project ID 73");
 }
}
?>