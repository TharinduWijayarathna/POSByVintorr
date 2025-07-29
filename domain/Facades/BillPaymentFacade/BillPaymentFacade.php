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
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
