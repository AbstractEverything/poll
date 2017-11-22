<?php

namespace Tests;

use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\Models\Vote;
use AbstractEverything\Poll\PollManager;
use AbstractEverything\Poll\VoteCaster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @expectedException AbstractEverything\Poll\Exceptions\PollClosedException
     */
    public function test_it_throws_exception_when_voting_on_closed_poll()
    {
        $pm = resolve(PollManager::class);
        $vc = resolve(VoteCaster::class);
        $poll = $pm->create($this->getSamplePollData(), true, true);
        $optionId = $poll->options()->first()->id;

        $vc->vote(1, $optionId);
    }

    /**
     * @expectedException AbstractEverything\Poll\Exceptions\DuplicateVoteException
     */
    public function test_it_disallows_multiple_votes()
    {
        $pm = resolve(PollManager::class);
        $vc = resolve(VoteCaster::class);
        $poll = $pm->create($this->getSamplePollData(), false);
        $optionId = $poll->options()->first()->id;

        $vc->vote(1, $optionId);
        // Attempt vote again to trigger exception
        $vc->vote(1, $optionId);
    }

    public function test_it_deletes_poll_including_options_and_votes()
    {
        $pm = resolve(PollManager::class);
        $vc = resolve(VoteCaster::class);
        $poll = $pm->create($this->getSamplePollData());
        $optionId = $poll->options()->first()->id;
        $vc->vote(1, $optionId);
        $pm->delete($poll->id);

        $this->assertEquals(0, Poll::count());
        $this->assertEquals(0, Option::count());
        $this->assertEquals(0, Vote::count());
    }
}
