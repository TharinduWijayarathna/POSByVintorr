<?php

namespace domain\Facades\EmployeeFacade;

use domain\Services\EmployeeService\EmployeeService;
use Illuminate\Support\Facades\Facade;

/**
 * PosCustomer Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
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
