<?php

namespace Grechanyuk\Peshkariki\Facades;

use Illuminate\Support\Facades\Facade;

class Peshkariki extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'peshkariki';
    }
}
