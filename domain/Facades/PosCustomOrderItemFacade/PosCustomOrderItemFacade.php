<?php

namespace domain\Facades\PosCustomOrderItemFacade;

use domain\Services\PosCustomOrderItemService\PosCustomOrderItemService;
use Illuminate\Support\Facades\Facade;

/**
 * PosCustomOrderItem Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class PosCustomOrderItemFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosCustomOrderItemService::class;
    }
}
