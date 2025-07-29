<?php

namespace domain\Facades\OutstandingReportFacade;

use domain\Services\OutstandingReportService\OutstandingReportService;
use Illuminate\Support\Facades\Facade;

/**
 * OutstandingReportFacade
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class OutstandingReportFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return OutstandingReportService::class;
    }
}
