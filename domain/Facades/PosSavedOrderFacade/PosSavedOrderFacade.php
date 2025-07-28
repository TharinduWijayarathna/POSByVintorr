<?php

namespace domain\Facades\PosSavedOrderFacade;

use domain\Services\PosSavedOrderService\PosSavedOrderService;
use Illuminate\Support\Facades\Facade;

/**
 * PosOrder Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class PosSavedOrderFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosSavedOrderService::class;
    }
}
