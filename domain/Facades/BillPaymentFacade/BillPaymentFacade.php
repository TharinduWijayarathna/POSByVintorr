<?php

namespace domain\Facades\BillPaymentFacade;

use domain\Services\BillPaymentService\BillPaymentService;
use Illuminate\Support\Facades\Facade;

/**
 * PosPayment Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
 * */
class BillPaymentFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return BillPaymentService::class;
    }
}
