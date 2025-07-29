<?php

namespace domain\Facades\CartFacade;

use domain\Services\CartService\CartService;
use Illuminate\Support\Facades\Facade;

/**
 * PosOrder Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
