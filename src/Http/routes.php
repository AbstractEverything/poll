<?php

Route::group([
    'prefix' => config('poll.route_prefix'),
    'namespace' => 'AbstractEverything\Poll\Http\Controllers',
], function() {
    Route::resource('polls', 'PollController', ['except' => ['edit', 'update']]);
    Route::resource('votes', 'VoteController', ['only' => ['store']]);
});
