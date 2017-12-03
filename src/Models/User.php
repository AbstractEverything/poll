<?php

/**
 * Note: this class is only used as a test stub it is not intended
 * to be used outside testing.
 */

namespace AbstractEverything\Poll\Models;

use AbstractEverything\Poll\Extras\PollUser;
use AbstractEverything\Poll\Extras\PollUserInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements PollUserInterface
{
    use PollUser;

    /**
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password',
    ];
}