@extends('poll::layouts.master')

@section('content')

@include('poll::forms.errors')

<h2>Results</h2>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ $poll->title }}</h3>
    </div>
    <div class="panel-body">
        @foreach ($poll->options as $option)
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $option->votesPercent($totalVotes) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $option->votesPercent($totalVotes) }}%; min-width: 2em;">
                    {{ $option->votesPercent($totalVotes) }}%
                </div>
            </div>
        @endforeach
        <p>Total votes: {{ $totalVotes }}</p>
        <hr />
        @include('poll::forms.options', ['options' => $poll->options])
    </div>
</div>

@auth(config('poll.admin_middleware'))
    <form action="{{ route('polls.destroy', $poll->id) }}" method="POST" role="form">
        <button type="submit" class="btn btn-danger">Delete poll</button>
    </form>
@endauth

@endsection