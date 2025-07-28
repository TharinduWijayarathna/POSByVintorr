<?php

namespace domain\Facades\PosOrderItemFacade;

use domain\Services\PosOrderItemService\PosOrderItemService;
use Illuminate\Support\Facades\Facade;

/**
 * PosOrderItem Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class PosOrderItemFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosOrderItemService::class;
    }
}
