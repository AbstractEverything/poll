<form action="{{ route('polls.create') }}" method="GET" role="form">
    <div class="form-inline">
        <input name="options" value="5" type="text" class="form-control" maxlength="2" size="2">
        <button type="submit" class="btn btn-default">New poll</button>
    </div>
</form>