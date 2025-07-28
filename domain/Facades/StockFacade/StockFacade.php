<?php

namespace domain\Facades\StockFacade;

use domain\Services\StockService\StockService;
use Illuminate\Support\Facades\Facade;

/**
 * Stock Facade
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class StockFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return StockService::class;
    }
}
