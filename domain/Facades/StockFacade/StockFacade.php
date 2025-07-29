<?php

namespace domain\Facades\StockFacade;

use domain\Services\StockService\StockService;
use Illuminate\Support\Facades\Facade;

/**
 * Stock Facade
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
