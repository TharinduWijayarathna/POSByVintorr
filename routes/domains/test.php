<?php

// Order Facade

use domain\Facades\PosOrderFacade\PosOrderFacade;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    $response['order'] = PosOrderFacade::get(2);
    // $pdf = PDF::loadView('print.pages.payment', $response)->setPaper([0, 0, 300, 700], 'portrait');
    // return $pdf->stream("Payment.pdf", array("Attachment" => false));
});
