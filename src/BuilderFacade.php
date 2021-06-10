<?php

namespace MkRyan1988\EloquentGaql;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MkRyan1988\EloquentGaql\Builder
 */
class BuilderFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'builder';
    }
}
