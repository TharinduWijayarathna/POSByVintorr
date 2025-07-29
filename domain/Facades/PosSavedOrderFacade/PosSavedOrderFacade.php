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
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
