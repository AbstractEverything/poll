[![Build Status](https://travis-ci.org/AbstractEverything/poll.svg?branch=master)](https://travis-ci.org/AbstractEverything/poll)

# Poll Package for Laravel 5.5

## Installation

Run `composer require 'abstracteverything/poll'`

OR add:

```
{
    "require": {
        "abstracteverything/poll": "dev-master"
    }
}
```

to your composer.json file and run `composer update`.

## Setup

Add `AbstractEverything\Poll\PollServiceProvider` to your providers array in `config/app.php`.

Optionally register the following two facades in `config/app.php`:

* `'Poll' => AbstractEverything\Poll\Facades\Poll::class,`
* `'Vote' => AbstractEverything\Poll\Facades\Vote::class,`

## Publishing assets

To publish package assets run: `php artisan vendor:publish` and choose `AbstractEverything\Poll\PollServiceProvider` this will publish the following files:

* Views to: `resources/vendor/abstracteverything/poll`
* Config to: `config/poll.php`
* Migrations to `database/migrations`

Run the database migrations by running `php artisan migrate`

## Setting up the poll user

The user class needs to implement the `AbstractEverything\Poll\Extras\PollUserInterface` interface and use the `AbstractEverything\Poll\Extras\PollUser` trait.

## Overriding default routes

The package ships with a default set of routes:

* `/polls` - polls.index (GET)
* `/polls` - polls.store (POST)
* `/polls/create` - polls.create (GET)
* `/polls/{poll}` - polls.show (GET)
* `/polls/{poll}` - polls.destroy (GET)
* `/votes` - votes.store (POST)

You can override these by redefining a route with the same name:

```
Route::get('custom_create_page', [
    'as' => 'polls.create',
    'uses' => '\AbstractEverything\Poll\Http\Controllers\PollController@create',
]);
```

## Middleware configuration

Routes for creating, destroying polls can be [protected by middleware](https://laravel.com/docs/5.5/middleware). In `config/poll.php` you can set which middleware should be used.

## Configuration

Other configuration options are avaliable in `config/poll.php`.