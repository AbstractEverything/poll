<?php

/**
 * If you want to override these routes make sure you register PollServiceProvider
 * before App\Providers\RouteServiceProvider in app.php you can then override
 * these routes with your own.
 */
Route::group([
    'namespace' => 'AbstractEverything\Poll\Http\Controllers',
], function() {
    Route::resource('polls', 'PollController', ['except' => ['edit', 'update']]);
    Route::resource('votes', 'VoteController', ['only' => ['store']]);
});
