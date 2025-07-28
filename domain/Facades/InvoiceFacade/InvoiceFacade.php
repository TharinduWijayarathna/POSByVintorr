<?php

namespace domain\Facades\InvoiceFacade;

use domain\Services\InvoiceService\InvoiceService;
use Illuminate\Support\Facades\Facade;

class InvoiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InvoiceService::class;
    }
}
