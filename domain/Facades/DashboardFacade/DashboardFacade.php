<?php

namespace domain\Facades\DashboardFacade;

use domain\Services\DashboardService\DashboardService;
use Illuminate\Support\Facades\Facade;

class DashboardFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DashboardService::class;
    }
}
