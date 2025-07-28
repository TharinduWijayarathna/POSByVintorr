<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tax\CreateTaxRequest;
use App\Http\Requests\Tax\UpdateTaxRequest;
use App\Http\Resources\GetProductsTaxResource;
use App\Http\Resources\GetTaxResource;
use App\Models\Tax;
use App\Models\User;
use domain\Facades\TaxFacade\TaxFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaxController extends ParentController
{
    /**
     * all
     *
     * @return void
     */
    public function all(Request $request)
    {
        $query = Tax::orderBy('name', 'asc');

        $payload = QueryBuilder::for($query)
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('abbreviation', 'like', "%{$value}%")
                        ->orWhere('rate', 'like', "%{$value}%");
                })
            )
            ->paginate(request('per_page', config('basic.pagination_per_page')));

        return GetTaxResource::collection($payload);
    }

    /**
     * list
     *
     * @return void
     */
    public function list()
    {
        $taxes = TaxFacade::all();

        return GetTaxResource::collection($taxes);
    }

    /**
     * store
     *
     * @param  mixed  $request
     * @return void
     */
    public function store(CreateTaxRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return TaxFacade::store($request->all());
        }
    }

    /**
     * storeProductTax
     *
     * @param  mixed  $product_id
     * @param  mixed  $request
     * @return void
     */
    public function storeProductTax(int $product_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return TaxFacade::storeProductTax($product_id, $request->all());
        }
    }

    /**
     * getProductTax
     *
     * @param  mixed  $product_id
     * @return void
     */
    public function getProductTax(int $product_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            $taxes = TaxFacade::getProductTax($product_id);

            return GetProductsTaxResource::collection($taxes);
        }
    }

    /**
     * deleteProductTax
     *
     * @param  mixed  $product_id
     * @param  mixed  $request
     * @return void
     */
    public function deleteProductTax(int $product_id, Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return TaxFacade::deleteProductTax($product_id, $request->all());
        }
    }

    /**
     * delete
     *
     * @param  mixed  $tax_id
     * @return void
     */
    public function delete($tax_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return TaxFacade::delete($tax_id);
        }
    }

    /**
     * get
     *
     * @param  mixed  $tax_id
     * @return void
     */
    public function get(int $tax_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return TaxFacade::get($tax_id);
        }
    }

    /**
     * getLatestTax
     *
     * @return void
     */
    public function getLatestTax()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return TaxFacade::getLatestTax();
        }
    }

    /**
     * update
     *
     * @param  mixed  $tax_id
     * @param  mixed  $request
     * @return void
     */
    public function update($tax_id, UpdateTaxRequest $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return TaxFacade::update($tax_id, $request->all());
        }
    }
}
