<?php

namespace domain\Facades\PayrollFacade;

use domain\Services\PayrollService\PayrollService;
use Illuminate\Support\Facades\Facade;

/**
 * Payroll Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class PayrollFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PayrollService::class;
    }
}
