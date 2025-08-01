<?php

namespace domain\Facades\EmployeeFacade;

use domain\Services\EmployeeService\EmployeeService;
use Illuminate\Support\Facades\Facade;

/**
 * PosCustomer Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class EmployeeFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return EmployeeService::class;
    }
}
