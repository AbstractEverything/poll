@if (session()->has('status.success'))
    <div class="alert alert-success" role="alert">
        {{ session()->get('status.success') }}
    </div>
@endif
