<form action="{{ route('votes.store') }}" method="POST" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="poll_id" id="poll_id" class="form-control" value="{{ $poll->id }}">
    @if ($poll->multichoice == false)
        {{-- Radio options --}}
        @foreach ($options as $option)
            <div class="radio">
                <label><input type="radio" name="options" value="{{ $option->id }}">{{ $option->label }}</label>
            </div>
        @endforeach
    @else
        {{-- Checkbox options --}}
        @foreach ($options as $option)
            <div class="checkbox">
                <label><input type="checkbox" name="options" value="{{ $option->id }}">{{ $option->label }}</label>
            </div>
        @endforeach
    @endif
    <button type="submit" class="btn btn-primary">Vote</button>
</form>
