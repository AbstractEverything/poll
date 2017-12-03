<?php

namespace AbstractEverything\Poll\Extras;

use AbstractEverything\Poll\Models\Poll;

interface PollUserInterface
{
    public function hasVoted(Poll $poll);
}