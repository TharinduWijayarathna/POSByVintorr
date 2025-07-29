<?php

namespace domain\Facades\StockLogFacade;

use domain\Services\StockLogService\StockLogService;
use Illuminate\Support\Facades\Facade;

/**
 * Stock Log Facade
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
