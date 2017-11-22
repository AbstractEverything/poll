@extends('poll::layouts.master')

@section('content')

<h2>Polls</h2>

@if ($polls->count() == 0)
    <p>There are no polls, create one below!</p>
@endif

@foreach ($polls as $poll)
    <div class="polls">
        <h3 class="poll-title"><a href="{{ route('polls.show', $poll->id) }}">{{ $poll->title }}</a></h3>
    </div>
@endforeach

{{ $polls->links() }}

<hr />

@include('poll::forms.create_options')

@endsection