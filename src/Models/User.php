<?php

/**
 * Note: this class is only used as a test stub it is not intended
 * to be used outside testing.
 */

namespace AbstractEverything\Poll\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password',
    ];
}