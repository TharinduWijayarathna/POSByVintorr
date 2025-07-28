<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateProductCategory;
use App\Http\Resources\DataResource;
use App\Http\Resources\GetCategoriesResource;
use App\Http\Resources\GetUnitsResource;
use App\Models\ProductCategory;
use App\Models\User;
use domain\Facades\CategoryFacade\CategoryFacade;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends ParentController
{
    public function all()
    {
        $categories = CategoryFacade::all();

        return GetCategoriesResource::collection($categories);
    }

    public function categoryAll()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = ProductCategory::where('name', '!=', 'Other')->orderBy('name', 'asc');

            $payload = QueryBuilder::for($query)
                ->allowedSorts(['id', 'name'])
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function store(CreateProductCategory $req)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return CategoryFacade::store($req->all());
        }
    }

    public function get($category_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return CategoryFacade::get($category_id);
        }
    }

    public function getLatestCategory()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return CategoryFacade::getLatestCategory();
        }
    }

    public function update(CreateProductCategory $request, int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            try {
                CategoryFacade::update($id, $request->validated());
            } catch (\Throwable $th) {
                return response()->json([
                    // 'message' => 'Product update failed'
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    public function delete($category_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return CategoryFacade::delete($category_id);
        }
    }

    public function unitsAll()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        $units = CategoryFacade::unitsAll();

        return GetUnitsResource::collection($units);
        // }
    }

    public function getCategory(int $category_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return CategoryFacade::getCategory($category_id);
        }
    }

    public function getUnit(int $unit_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return CategoryFacade::getUnit($unit_id);
        }
    }
}
