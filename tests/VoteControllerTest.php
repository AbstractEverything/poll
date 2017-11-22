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
        $pm = resolve(PollManager::class);
        $poll = $pm->create($this->getSamplePollData(), true, true);
        $user = $this->makeTestUser();

        $response = $this->actingAs($user)
            ->post('/votes', [
                'user_id' => $user->id,
                'option_id' => $poll->options()->first()->id,
            ]);

        $this->assertEquals(0, Vote::count());
    }

    public function test_it_does_allow_votes_in_polls_where_only_one_is_allowed()
    {
        $pm = resolve(PollManager::class);
        $poll = $pm->create($this->getSamplePollData(), false);
        $user = $this->makeTestUser();

        // make two votes the second should fail
        $this->actingAs($user)
            ->post('/votes', [
                'user_id' => $user->id,
                'option_id' => $poll->options()->first()->id,
            ]);
        $this->actingAs($user)
            ->post('/votes', [
                'user_id' => $user->id,
                'option_id' => $poll->options()->first()->id,
            ]);

        $this->assertEquals(1, Vote::count());
    }
}