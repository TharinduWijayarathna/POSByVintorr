<?php

namespace domain\Facades\ReportFacade;

use domain\Services\ReportService\ReportService;
use Illuminate\Support\Facades\Facade;

/**
 * Report Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
