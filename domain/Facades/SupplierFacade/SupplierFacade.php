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
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
