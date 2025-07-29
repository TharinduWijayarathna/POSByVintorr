<?php

namespace domain\Facades\PosCustomOrderFacade;

use domain\Services\PosCustomOrderService\PosCustomOrderService;
use Illuminate\Support\Facades\Facade;

/**
 * PosCustomOrder Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosCustomOrderFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosCustomOrderService::class;
    }
}
