<?php

namespace VasilGerginski\MarketingSuite\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VasilGerginski\MarketingSuite\MarketingSuite
 */
class MarketingSuite extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VasilGerginski\MarketingSuite\MarketingSuite::class;
    }
}
