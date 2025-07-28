<?php

namespace domain\Facades\DatabaseFacade;

use domain\Services\DatabaseService\DatabaseService;
use Illuminate\Support\Facades\Facade;

class DatabaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DatabaseService::class;
    }
}
