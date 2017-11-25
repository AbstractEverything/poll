<?php

namespace AbstractEverything\Poll\Facades;

use Illuminate\Support\Facades\Facade;

class Vote extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'vote.caster'; }
}