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

To publish package assets run: `php artisan vendor:publish --provider=AbstractEverything\Poll\PollServiceProvider` this will publish the following files:

* Views to: `resources/vendor/abstracteverything/poll`
* Config to: `config/poll.php`
* Migrations to `database/migrations`