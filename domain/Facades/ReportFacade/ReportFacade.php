<?php

namespace domain\Facades\ReportFacade;

use domain\Services\ReportService\ReportService;
use Illuminate\Support\Facades\Facade;

/**
 * Report Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class ReportFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return ReportService::class;
    }
}
