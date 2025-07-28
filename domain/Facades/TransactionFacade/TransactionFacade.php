<?php

namespace domain\Facades\TransactionFacade;

use domain\Services\TransactionService\TransactionService;
use Illuminate\Support\Facades\Facade;

class TransactionFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return TransactionService::class;
    }
}
