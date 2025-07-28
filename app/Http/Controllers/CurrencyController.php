<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetCurrencyListResource;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function list()
    {
        $currency = Currency::query()->orderBy('code', 'asc')->get();
        return GetCurrencyListResource::collection($currency);
    }
}
