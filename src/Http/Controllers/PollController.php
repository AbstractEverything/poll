<?php

namespace AbstractEverything\Poll\Http\Controllers;

use AbstractEverything\Poll\Exceptions\PollOptionsException;
use AbstractEverything\Poll\Http\Requests\CreatePollRequest;
use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\PollManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class PollController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var AbstractEverything\Poll\Models\Poll
     */
    protected $poll;


    /**
     * @var AbstractEverything\Poll\PollManager
     */
    protected $pollManager;

    /**
     * Constructor
     * 
     * @param Poll        $poll
     * @param PollManager $pollManager
     */
    public function __construct(Poll $poll, PollManager $pollManager)
    {
        $this->poll = $poll;
        $this->pollManager = $pollManager;

        $this->middleware(config('poll.admin_middleware'), [
            'only' => ['create', 'store', 'destroy'],
        ]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polls = $this->poll->paginate(config('poll.per_page'));

        return view(config('poll.views_base_path').'index', compact('polls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'Create new poll';
        $options = ($request->input('options') > 10) ? 10 : $request->input('options');

        return view(config('poll.views_base_path').'create', compact('title', 'options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePollRequest $request)
    {
        try
        {
            $poll = $this->pollManager->create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'options' => $request->input('options'),
            ]);
        }
        catch (PollOptionsException $e)
        {
            abort(400, 'At poll must contain between 1 and '.config('max_options').' options');
        }

        return redirect()
            ->route('polls.index')
            ->with('status.success', 'Poll created succesfully');
    }   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poll = $this->pollManager->results($id);
        $totalVotes = $poll->totalVotes();

        return view(config('poll.views_base_path').'show', compact('poll', 'totalVotes'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->pollManager->delete($id);

        return redirect()
            ->route('poll::index')
            ->with('status.success', 'Poll deleted succesfully');
    }
}
