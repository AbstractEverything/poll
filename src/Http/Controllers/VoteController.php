<?php

namespace AbstractEverything\Poll\Http\Controllers;

use AbstractEverything\Poll\Exceptions\DuplicateVoteException;
use AbstractEverything\Poll\Exceptions\PollClosedException;
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

        $this->validate($request, [
            'option_id' => 'exists:options,id',
        ]);

        try
        {
            $this->voteCaster->cast(auth()->user()->id, $request->input('option_id'));
        }
        catch (DuplicateVoteException $e)
        {
            abort(400, 'You have already voted in this poll');
        }
        catch (PollClosedException $e)
        {
            abort(400, 'This poll is closed');
        }

        return redirect()
            ->route(config('poll.views_base_path').'show', $request->input('poll_id'))
            ->with('status', 'Voted successfully');
    }
}
