<?php

namespace domain\Facades\PosAdvanceReceiptFacade;

use domain\Services\PosAdvanceReceiptService\PosAdvanceReceiptService;
use Illuminate\Support\Facades\Facade;

/**
 * PosAdvanceReceipt Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosAdvanceReceiptFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosAdvanceReceiptService::class;
    }
}
