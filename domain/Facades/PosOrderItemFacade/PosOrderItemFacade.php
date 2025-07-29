<?php

namespace domain\Facades\PosOrderItemFacade;

use domain\Services\PosOrderItemService\PosOrderItemService;
use Illuminate\Support\Facades\Facade;

/**
 * PosOrderItem Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
