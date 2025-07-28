<?php

namespace domain\Facades\QuotationFacade;

use domain\Services\QuotationService\QuotationService;
use Illuminate\Support\Facades\Facade;

class QuotationFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return QuotationService::class;
    }
}
