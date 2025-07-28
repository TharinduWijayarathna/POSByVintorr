<?php

namespace domain\Facades\ExpenseFacade;

use domain\Services\ExpenseService\ExpenseService;
use Illuminate\Support\Facades\Facade;

class ExpenseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ExpenseService::class;
    }
}
