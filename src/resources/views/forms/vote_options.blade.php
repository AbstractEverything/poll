<form action="{{ route('votes.store') }}" method="POST" role="form">
    {{ csrf_field() }}
    {{-- redirect to this poll id --}}
    <input type="hidden" name="poll_id" id="poll_id" class="form-control" value="{{ $poll->id }}">
    @foreach ($options as $option)
        <div class="radio">
            <label><input type="radio" name="option_id" id="option-radio" value="{{ $option->id }}">{{ $option->label }}</label>
        </div>
    @endforeach
    <button type="submit" class="btn btn-primary">Vote</button>
</form>
