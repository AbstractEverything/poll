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

    <div class="form-group">
        <label for="title">Ends at (optional)</label>
        <input name="ends_at" type="text" value="{{ old('ends_at') }}" class="form-control" id="ends_at" placeholder="E.g. 2018/07/09">
    </div>

    <select name="multichoice" id="multichoice" class="form-control">
        <option value="0">Single choice</option>
        <option value="1">Multiple choice</option>
    </select>

    <button type="submit" class="btn btn-primary">Make poll</button>
</form>