<?php

namespace domain\Facades\PosPaymentFacade;

use domain\Services\PosPaymentService\PosPaymentService;
use Illuminate\Support\Facades\Facade;

/**
 * PosPayment Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class PosPaymentFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosPaymentService::class;
    }
}
