<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../app/Components/Voting/Models/VoteResponse.php';
class VoteResponseTest extends TestCase
{
    public function testCreateVoteResponse()
    {
        $data = [
            'vote_id' => 90,
            'user_id' => 137,
            'selected_option' => 'no'
        ];
        
        $response = VoteResponse::createVoteResponse($data);
        
        $this->assertInstanceOf(VoteResponse::class, $response);
        $this->assertEquals(90, $response->getVoteId());
        $this->assertEquals(137, $response->getUserId());
        $this->assertEquals('no', $response->getSelectedOption());
    }

    public function testGetUserVoteResponse()
    {
        $vote_id = 90;
        $user_id = 137;

        $selected_option = VoteResponse::getUserVoteResponse($vote_id, $user_id);
        $this->assertIsString($selected_option);
        $this->assertContains($selected_option, ['yes', 'no', 'abstain', null], "Expected options are 'yes', 'no', 'abstain' or null");
    }

    public function testHasUserVoted()
    {
        $vote_id = 90;
        $user_id = 137;

        $hasVoted = VoteResponse::hasUserVoted($vote_id, $user_id);
        $this->assertIsBool($hasVoted);
    }
}
?>