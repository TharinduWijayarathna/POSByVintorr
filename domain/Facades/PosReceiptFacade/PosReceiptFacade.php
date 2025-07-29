<?php

namespace domain\Facades\PosReceiptFacade;

use domain\Services\PosReceiptService\PosReceiptService;
use Illuminate\Support\Facades\Facade;

/**
 * PosReceipt Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosReceiptFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PosReceiptService::class;
    }
}
