@extends('poll::layouts.master')

@section('content')

<h2>Create new poll</h2>

@include('poll::forms.errors')

<form action="{{ route('polls.store') }}" method="POST" role="form">

    {{ csrf_field() }}

    <div class="form-group">
        <label for="title">Poll title</label>
        <input name="title" type="text" value="{{ old('title') }}" class="form-control" id="title" placeholder="Title...">
    </div>

    <div class="form-group">
        <label for="body">Poll description</label>
        <textarea name="description" id="description" class="form-control" rows="3" required="required" placeholder="Description...">{{ old('description') }}</textarea>
    </div>

    <legend>Options</legend>

    @for ($i = 0; $i < $options; $i++)
        <div class="form-group">
            <label for="options[]">Option {{ $i + 1 }}</label>
            <input name="options[]" value="{{ old('options.'.$i) }}" type="text" class="form-control" placeholder="Option {{ $i + 1 }}...">
        </div>
    @endfor

    <button type="submit" class="btn btn-primary">Make poll</button>
</form>

@endsection