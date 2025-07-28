<?php

namespace App\Http\Controllers;

use App\Http\Requests\UOM\CreateUOMRequest;
use App\Http\Requests\UOM\UpdateUOMRequest;
use App\Http\Resources\DataResource;
use App\Http\Resources\GetUOMResource;
use App\Models\Unit;
use App\Models\User;
use domain\Facades\UnitOfMeasureFacade\UnitOfMeasureFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class UOMController extends Controller
{
    
    /**
     * All
     * get all paginated uom
     *
     * @param Request $request 
     *
     * @return void
     */
    public function all(Request $request)
    {
        $query = Unit::query();
        $payload = QueryBuilder::for($query)
            ->paginate(request('per_page', config('basic.pagination_per_page')));
        return GetUOMResource::collection($payload);

    }
    
    /**
     * Store
     * store new uom
     *
     * @param Request $request  
     *
     * @return void
     */
    public function store(CreateUOMRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN']) {
            return UnitOfMeasureFacade::store($request->all());
        }
    }
    
    /**
     * Delete
     * delete uom using uom_id
     *
     * @param $uom_id  
     *
     * @return void
     */
    public function delete($uom_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return UnitOfMeasureFacade::delete($uom_id);
        }
    }
    
    /**
     * Get
     * get specific uom using 
     *
     * @param int $uom_id 
     *
     * @return void
     */
    public function get(int $uom_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return UnitOfMeasureFacade::get($uom_id);
        }
    }
    
    /**
     * Update
     * update UOM using uom_id
     *
     * @param $uom_id 
     * @param Request $request 
     *
     * @return void
     */
    public function update($uom_id, UpdateUOMRequest $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return UnitOfMeasureFacade::update($uom_id, $request);
        }
    }
}
