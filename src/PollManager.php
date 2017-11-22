<?php

namespace AbstractEverything\Poll;

use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\Models\Vote;
use AbstractEverything\Poll\Exceptions\DuplicateVoteException;
use AbstractEverything\Poll\Exceptions\PollClosedException;
use AbstractEverything\Poll\Exceptions\PollOptionsException;
use Exception;
use Illuminate\Database\DatabaseManager as DBM;

class PollManager
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
     * @var Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * Constructor
     * 
     * @param Poll   $poll
     * @param Option $option
     */
    public function __construct(Poll $poll, Option $option, Vote $vote, DBM $db)
    {
        $this->poll = $poll;
        $this->option = $option;
        $this->vote = $vote;
        $this->db = $db;
    }

    /**
     * Create a new poll
     * 
     * @param  array   $input
     * @param  boolean $multivote
     * @param  boolean $closed
     * @return App\Models\Poll
     */
    public function create(array $input = [], $multivote = true, $closed = false)
    {
        if (count($input['options']) <= 1 || count($input['options']) > config('poll.max_options'))
        {
            throw new PollOptionsException;
        }

        if ( ! isset($input['ends_at'])) $input['ends_at'] = null;

        $this->db->beginTransaction();

        try
        {
            $poll = $this->poll->create([
                'title' => $input['title'],
                'description' => $input['description'],
                'closed' => $closed,
                'multivote' => $multivote,
                'ends_at' => $input['ends_at'],
            ]);

            $poll->options()->saveMany($this->makeOptions($input['options']));

            $this->db->commit();
        }
        catch (Exception $e)
        {
            $this->db->rollback();

            throw $e;
        }

        return $poll;
    }

    /**
     * Make an array of Option objects
     * 
     * @param  array  $options
     * @return array
     */
    protected function makeOptions(array $options = [])
    {
        $opt = [];

        foreach ($options as $option)
        {
            $opt[] = new Option(['label' => $option]);
        }

        return $opt;
    }

    /**
     * Open a poll
     * 
     * @param  integer $id
     * @return boolean
     */
    public function open($id)
    {
        return $this->poll->where('id', $id)->update([
            'closed' => false,
        ]);
    }

    /**
     * Close a poll
     * 
     * @param  integer $id
     * @return boolean
     */
    public function close($id)
    {
        return $this->poll->where('id', $id)->update([
            'closed' => true,
        ]);
    }

    /**
     * Poll results
     * 
     * @param  integer $id
     * @return App\Models\Poll
     */
    public function results($id)
    {
        return $this->poll->with('options.votesCount')->find($id);
    }

    /**
     * Delete the poll
     * 
     * @param  integer $id
     * @return null
     */
    public function delete($id)
    {
        $poll = $this->poll->find($id);

        $voteIds = $this->poll->find($id)->votes->modelKeys();
        $this->vote->whereIn('id', $voteIds)->delete();

        $poll->options()->delete();
        
        return $poll->delete();
    }
}