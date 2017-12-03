<?php

namespace Tests;

use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\Models\Vote;
use AbstractEverything\Poll\PollManager;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class VoteControllerTest extends TestCase
{
    public function test_it_does_not_allow_votes_in_closed_polls()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['closed'] = true;

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => $poll->options()->first()->id,
            ]);

        $this->assertEquals(0, Vote::count());
    }

    public function test_it_does_not_allow_votes_in_polls_where_only_one_is_allowed()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['multichoice'] = false;

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        // make two votes the second should fail
        $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => $poll->options()->first()->id,
            ]);
        $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => $poll->options()->first()->id,
            ]);

        $this->assertEquals(1, Vote::count());
    }

    public function test_it_allows_votes_within_closing_date()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['ends_at'] = '2100-01-01 00:00:00';

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => $poll->options()->first()->id,
            ]);

        $this->assertEquals(1, Vote::count());
        $response->assertRedirect("/polls/{$poll->id}");
    }

    public function test_it_disallows_votes_past_closing_date()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['ends_at'] = '2010-01-01 00:00:00';

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => $poll->options()->first()->id,
            ]);

        $this->assertEquals(0, Vote::count());
        $response->assertStatus(400);
        $response->assertSeeText('You can no longer vote in this poll');
    }

    public function test_it_can_process_a_multichoice_vote()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['multichoice'] = false;

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => [1, 2],
            ]);

        $this->assertEquals(2, Vote::count());
    }

    public function test_it_canno_vote_in_closed_polls()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['multichoice'] = false;
        $sampleData['closed'] = true;

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => [1, 2],
            ]);

        $this->assertEquals(0, Vote::count());
        $response->assertStatus(400);
        $response->assertSeeText('This poll is closed');
    }

    public function test_it_detects_invalid_single_vote_option()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['multichoice'] = false;

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => 777,
            ]);

        $this->assertEquals(0, Vote::count());
        $response->assertStatus(400);
        $response->assertSeeText('You voted on an option that does not exist');
    }

    public function test_it_detects_invalid_multivote_options()
    {
        $sampleData = $this->getSamplePollData();
        $sampleData['multichoice'] = false;

        $pm = resolve(PollManager::class);
        $poll = $pm->create($sampleData);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'poll_id' => $poll->id,
                'options' => [1, 777],
            ]);

        $this->assertEquals(0, Vote::count());
        $response->assertStatus(400);
        $response->assertSeeText('You voted on an option that does not exist');
    }
}