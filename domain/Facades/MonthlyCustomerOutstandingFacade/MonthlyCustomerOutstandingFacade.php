<?php

namespace domain\Facades\MonthlyCustomerOutstandingFacade;

use domain\Services\MonthlyCustomerOutstandingService\MonthlyCustomerOutstandingService;
use Illuminate\Support\Facades\Facade;

/**
 * Permission Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class MonthlyCustomerOutstandingFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return MonthlyCustomerOutstandingService::class;
    }
}
