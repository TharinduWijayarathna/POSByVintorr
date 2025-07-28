<?php

namespace domain\Facades\ProductSalesFacade;

use domain\Services\ProductSalesService\ProductSalesService;
use Illuminate\Support\Facades\Facade;

/**
 * ProductSalesFacade
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
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
