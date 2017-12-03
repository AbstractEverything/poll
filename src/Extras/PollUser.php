<?php

namespace AbstractEverything\Poll\Extras;

use AbstractEverything\Poll\Models\Poll;

trait PollUser
{
    /**
     * Has this user id already voted in this poll
     * 
     * @param  integer  $userId
     * @return boolean
     */
    public function hasVoted(Poll $poll)
    {
        return $poll->votes()->where('user_id', $this->id)->count() > 0;
    }
}