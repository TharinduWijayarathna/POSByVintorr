<?php

namespace domain\Facades\OutstandingReportFacade;

use domain\Services\OutstandingReportService\OutstandingReportService;
use Illuminate\Support\Facades\Facade;

/**
 * OutstandingReportFacade
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
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
