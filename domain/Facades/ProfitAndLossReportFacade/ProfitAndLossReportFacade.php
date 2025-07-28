<?php

namespace domain\Facades\ProfitAndLossReportFacade;

use domain\Services\ProfitAndLossReportService\ProfitAndLossReportService;
use Illuminate\Support\Facades\Facade;

/**
 * ProfitAndLossReportFacade
 *
 * @category Facade
 *
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 *
 * @link     https://emergentspark.com
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
