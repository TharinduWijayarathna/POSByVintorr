<?php

namespace App\Http\Controllers;

use domain\Facades\PosCashierDailyRecordFacade\PosCashierDailyRecordFacade;

class PosCashierDailyRecordController extends ParentController
{
    /**
     * store
     * Store customer details
     *
     * @param  decimal $amount
     * @return void
     */
    public function store(float $start_amount)
    {
        return PosCashierDailyRecordFacade::store($start_amount);
    }

    /**
     * update
     *
     * @param  decimal $end_amount
     * @return void
     */
    public function update(float $end_amount)
    {
        return PosCashierDailyRecordFacade::update($end_amount);
    }


    /**
     * get
     *
     * @return void
     */
    public function get()
    {
        return PosCashierDailyRecordFacade::get();
    }
}
