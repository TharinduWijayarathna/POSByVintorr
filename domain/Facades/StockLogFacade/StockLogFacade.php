<?php

namespace domain\Facades\StockLogFacade;

use domain\Services\StockLogService\StockLogService;
use Illuminate\Support\Facades\Facade;

/**
 * Stock Log Facade
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class StockLogFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return StockLogService::class;
    }
}
