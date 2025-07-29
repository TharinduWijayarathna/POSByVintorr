<?php

namespace domain\Facades\ProfitAndLossReportFacade;

use domain\Services\ProfitAndLossReportService\ProfitAndLossReportService;
use Illuminate\Support\Facades\Facade;

/**
 * ProfitAndLossReportFacade
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class ProfitAndLossReportFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return ProfitAndLossReportService::class;
    }
}
