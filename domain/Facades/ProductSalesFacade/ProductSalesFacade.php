<?php

namespace domain\Facades\ProductSalesFacade;

use domain\Services\ProductSalesService\ProductSalesService;
use Illuminate\Support\Facades\Facade;

/**
 * ProductSalesFacade
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class ProductSalesFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return ProductSalesService::class;
    }
}
