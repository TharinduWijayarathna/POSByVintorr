<?php

namespace domain\Facades\PosOrderFacade;

use domain\Services\PosOrderService\PosOrderService;
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
class PosOrderFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosOrderService::class;
    }
}
