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
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
