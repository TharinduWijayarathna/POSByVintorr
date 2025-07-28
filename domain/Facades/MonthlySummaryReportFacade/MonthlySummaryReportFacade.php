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
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
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
