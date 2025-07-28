<?php

namespace domain\Facades\UnitOfMeasureFacade;

use domain\Services\UnitOfMeasureService\UnitOfMeasureService;
use Illuminate\Support\Facades\Facade;

class UnitOfMeasureFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return UnitOfMeasureService::class;
    }
}
