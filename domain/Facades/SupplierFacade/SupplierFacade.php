<?php

namespace domain\Facades\SupplierFacade;

use domain\Services\SupplierService\SupplierService;
use Illuminate\Support\Facades\Facade;

/**
 * PosCustomer Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class SupplierFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return SupplierService::class;
    }
}
