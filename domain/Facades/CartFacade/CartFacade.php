<?php

namespace domain\Facades\CartFacade;

use domain\Services\CartService\CartService;
use Illuminate\Support\Facades\Facade;

/**
 * PosOrder Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class CartFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return CartService::class;
    }
}
