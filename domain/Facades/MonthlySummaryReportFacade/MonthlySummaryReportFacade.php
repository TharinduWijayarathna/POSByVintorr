<?php

namespace domain\Facades\MonthlySummaryReportFacade;

use domain\Services\MonthlySummaryReportService\MonthlySummaryReportService;
use Illuminate\Support\Facades\Facade;

/**
 * Permission Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class MonthlySummaryReportFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return MonthlySummaryReportService::class;
    }
}
