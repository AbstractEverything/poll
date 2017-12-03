<?php

namespace AbstractEverything\Poll;

use AbstractEverything\Poll\Exceptions\DuplicateVoteException;
use AbstractEverything\Poll\Exceptions\PollClosedException;
use AbstractEverything\Poll\Exceptions\PollTimeoutException;
use AbstractEverything\Poll\Exceptions\InvalidOptionException;
use AbstractEverything\Poll\Extras\PollUserInterface;
use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\Models\Vote;
use Carbon\Carbon;
use Exception;

class VoteCaster
{
    /**
     * @var App\Models\Poll
     */
    protected $poll;

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
    public function __construct(Poll $poll, Option $option, Vote $vote)
    {
        $this->poll = $poll;
        $this->option = $option;
        $this->vote = $vote;
    }

    /**
     * Cast a vote for a single option or array of options
     * 
     * @param  PollUserInterface $user
     * @param  integer           $pollId
     * @param  integer|array     $option
     * @return App\Models\Vote
     */
    public function cast(PollUserInterface $user, $pollId, $option)
    {
        if (is_array($option))
        {
            $this->multichoice($user, $pollId, $option);
        }

        try
        {
            $option = $this->option->findOrFail($option);
            $poll = $option->poll;
        }
        catch (Exception $e)
        {
            throw new InvalidOptionException;
        }

        if ($poll->multichoice == false && $user->hasVoted($poll))
        {
            throw new DuplicateVoteException;
        }

        if ($poll->closed == true)
        {
            throw new PollClosedException;
        }

        if ($poll->ends_at != null && Carbon::parse($poll->ends_at)->isPast())
        {
            throw new PollTimeoutException;
        }

        return $this->vote->create([
            'user_id' => $user->id,
            'option_id' => $option->id,
        ]);
    }

    /**
     * Cast a vote for multiple options making sure the poll options that
     * the user voted for match the poll options avaliable to this poll
     * 
     * @param  UserInterface $user
     * @param  integer       $pollId
     * @param  array         $options
     * @return boolean
     */
    protected function multichoice(PollUserInterface $user, $pollId, array $options = [])
    {
        $poll = $this->poll->with('options')->findOrFail($pollId);
        $pollOptions = $poll->options()->pluck('id')->toArray();

        if ($pollOptions != $options)
        {
            throw new InvalidOptionException;
        }

        if ($poll->multichoice == false && $user->hasVoted($poll))
        {
            throw new DuplicateVoteException;
        }

        if ($poll->closed == true)
        {
            throw new PollClosedException;
        }

        if ($poll->ends_at != null && Carbon::parse($poll->ends_at)->isPast())
        {
            throw new PollTimeoutException;
        }

        $now = Carbon::now()->toDateTimeString();
        $data = [];

        foreach ($options as $option)
        {
            $data[] = [
                'user_id' => $user->id,
                'option_id' => $option,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $this->vote->insert($data);
    }
}