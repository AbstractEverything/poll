<?php

namespace AbstractEverything\Poll\Http\Controllers;

use AbstractEverything\Poll\Exceptions\DuplicateVoteException;
use AbstractEverything\Poll\Exceptions\InvalidOptionException;
use AbstractEverything\Poll\Exceptions\PollClosedException;
use AbstractEverything\Poll\Exceptions\PollTimeoutException;
use AbstractEverything\Poll\VoteCaster;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class VoteController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var AbstractEverything\Poll\VoteCaster
     */
    protected $voteCaster;

    /**
     * Constructor
     * 
     * @param VoteCaster $voteCaster
     */
    public function __construct(VoteCaster $voteCaster)
    {
        $this->voteCaster = $voteCaster;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ( ! auth()->check())
        {
            abort(403, 'Not logged in!');
        }

        $request->validate([
            'poll_id' => 'exists:polls,id',
        ]);

        try
        {
            $this->voteCaster->cast(
                auth()->user(),
                $request->input('poll_id'),
                $request->input('options')
            );
        }
        catch (DuplicateVoteException $e)
        {
            abort(400, 'You have already voted in this poll');
        }
        catch (PollClosedException $e)
        {
            abort(400, 'This poll is closed');
        }
        catch (PollTimeoutException $e)
        {
            abort(400, 'You can no longer vote in this poll');
        }
        catch (InvalidOptionException $e)
        {
            abort(400, 'You voted on an option that does not exist');
        }

        return redirect()
            ->route('polls.show', $request->input('poll_id'))
            ->with('status', 'Voted successfully');
    }
}
