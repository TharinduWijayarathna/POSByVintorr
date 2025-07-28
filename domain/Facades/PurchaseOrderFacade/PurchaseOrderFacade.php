<?php

namespace domain\Facades\PurchaseOrderFacade;

use domain\Services\PurchaseOrderService\PurchaseOrderService;
use Illuminate\Support\Facades\Facade;

class PurchaseOrderFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PurchaseOrderService::class;
    }
}
