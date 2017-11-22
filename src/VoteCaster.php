<?php

namespace AbstractEverything\Poll;

use AbstractEverything\Poll\Exceptions\DuplicateVoteException;
use AbstractEverything\Poll\Exceptions\PollClosedException;
use AbstractEverything\Poll\Exceptions\PollTimeoutException;
use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Vote;
use Carbon\Carbon;

class VoteCaster
{
    /**
     * @var App\Models\Option
     */
    protected $option;

    /**
     * @var App\Models\Vote
     */
    protected $vote;

    /**
     * Constructor
     * 
     * @param Poll   $poll
     * @param Option $option
     */
    public function __construct(Option $option, Vote $vote)
    {
        $this->option = $option;
        $this->vote = $vote;
    }

    /**
     * Cast vote
     * 
     * @param  integer $userId
     * @param  integer $optionId
     * @return App\Models\Vote
     */
    public function vote($userId, $optionId)
    {
        $poll = $this->option->find($optionId)->poll;

        if ($poll->closed == true)
        {
            throw new PollClosedException;
        }

        if ($poll->ends_at != null && Carbon::parse($poll->ends_at)->isPast())
        {
            throw new PollTimeoutException;
        }

        if ($poll->multivote == false && $poll->hasVoted($userId))
        {
            throw new DuplicateVoteException;
        }

        return $this->vote->create([
            'user_id' => $userId,
            'option_id' => $optionId,
        ]);
    }
}