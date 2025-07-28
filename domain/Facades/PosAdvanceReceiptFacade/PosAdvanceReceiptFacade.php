<?php

namespace domain\Facades\PosAdvanceReceiptFacade;

use domain\Services\PosAdvanceReceiptService\PosAdvanceReceiptService;
use Illuminate\Support\Facades\Facade;

/**
 * PosAdvanceReceipt Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
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
