<?php

namespace AbstractEverything\Poll\Extras;

use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\Models\Vote;

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

    /**
     * Votes relation
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}