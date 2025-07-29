<?php

namespace domain\Facades\PayrollFacade;

use domain\Services\PayrollService\PayrollService;
use Illuminate\Support\Facades\Facade;

/**
 * Payroll Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
