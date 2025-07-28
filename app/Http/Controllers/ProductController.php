<?php

namespace App\Http\Controllers;

use App\Http\Resources\GetProductResource;
use App\Models\User;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\DataResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use domain\Facades\ImageFacade\ImageFacade;
use domain\Facades\ProductFacade\ProductFacade;
use App\Http\Requests\Product\UpdateStockRequest;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Imports\ProductsImport;
use PDF;
use domain\Facades\UserFacade\UserFacade;
use Exception;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends ParentController
{

    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Product/index');
        }
    }

    public function allRolProducts(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if ($id == 2) {
                // $response = UserFacade::retrieveHost();
                $response['check_rol'] = $id;
                return Inertia::render('Product/index');
            }
        }
    }

    public function get(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $product = ProductFacade::get($id);

            if ($product == null) {
                $product = ProductFacade::getWithDeleted($id);
            }

            return $product;
        }
    }

    public function getOrderItem(int $pos_order_item_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return ProductFacade::getOrderItem($pos_order_item_id, $order->id);
        }
    }

    public function storeCategory()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Product/category');
        }
    }

    public function store(CreateProductRequest $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if ($request->has('image')) {
                $image = ImageFacade::convertImageToWebP($request->file('image'));
                $request->merge(['image_id' => $image->id]);
            }
            return ProductFacade::store($request->all());
        }
    }

    public function cartAll()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
                return ProductFacade::allForInspector();
            } else {
                return ProductFacade::all();
            }
        }
    }

    public function getLimitedProducts()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
                return ProductFacade::getInspectorLimitedProducts();
            } else {
                return ProductFacade::getLimitedProducts();
            }
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
                $query = Product::where('status', 0)->where('visibility', Product::VISIBILITY['visible'])->orderBy('id', 'desc');
            } else {
                $query = Product::where('status', 0)->orderBy('id', 'desc');
            }

            $payload = $query->where(function ($query) use ($request) {
                if (isset($request->search_product_code)) {
                    $query->where('code', 'like', '%' . $request->search_product_code . '%');
                }
                if (isset($request->search_product_name)) {
                    $query->where('name', 'like', '%' . $request->search_product_name . '%');
                }
                if (isset($request->search_product_selling_max) && isset($request->search_product_selling_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_selling_min));
                    $max = floatval(str_replace(",", "", $request->search_product_selling_max));
                    if ($max != 0) {
                        $query->where('selling', '>=', $min)->where('selling', '<=', $max);
                    } else {
                        $query->where('selling', '>=', $min);
                    }
                } else if (isset($request->search_product_selling_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_selling_min));
                    $query->where('selling', '>=', $min);
                } else if (isset($request->search_product_selling_max)) {
                    $max = floatval(str_replace(",", "", $request->search_product_selling_max));
                    if ($max != 0) {
                        $query->where('selling', '<=', $max);
                    }
                }
                if (isset($request->search_product_cost_max) && isset($request->search_product_cost_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_cost_min));
                    $max = floatval(str_replace(",", "", $request->search_product_cost_max));
                    if ($max != 0) {
                        $query->where('cost', '>=', $min)->where('cost', '<=', $max);
                    } else {
                        $query->where('cost', '>=', $min);
                    }
                } else if (isset($request->search_product_cost_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_cost_min));
                    $query->where('cost', '>=', $min);
                } else if (isset($request->search_product_cost_max)) {
                    $max = floatval(str_replace(",", "", $request->search_product_cost_max));
                    if ($max != 0) {
                        $query->where('cost', '<=', $max);
                    }
                }
                if (isset($request->search_order_stock)) {
                    if ($request->search_order_stock == 1) {
                        $query->where('product_type', 1);
                        $query->where(function ($query) {
                            $query->where('stock_quantity', '<=', 0)
                                ->orWhereNull('stock_quantity');
                        });
                    }
                    if ($request->search_order_stock == 2) {
                        $rol_products = $query->get();
                        $filtered_products = [];
                        foreach ($rol_products as $rol_product) {
                            if (floatval($rol_product->rol) > 0 && floatval($rol_product->rol) >= floatval($rol_product->stock_quantity)) {
                                $filtered_products[] = $rol_product;
                            }
                        }
                        $query->whereIn('id', collect($filtered_products)->pluck('id'));
                    }
                }
            });
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['id', 'name'])->with('unit')
                ->paginate(request('per_page', config('basic.pagination_per_page')));
            return DataResource::collection($payload);
        }
    }

    public function search(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $payload = ProductFacade::search($request['params']['search']);
            return DataResource::collection($payload);
        }
    }

    public function getWithDetails(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::getWithDetails($id);
        }
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            try {
                if ($request->has('image')) {
                    $image = ImageFacade::convertImageToWebP($request->file('image'));
                    $request->merge(['image_id' => $image->id]);
                }
                return ProductFacade::update($id, $request->all());
            } catch (\Throwable $th) {
                return response()->json([
                    // 'message' => 'Product update failed'
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    public function stockUpdate(UpdateStockRequest $request, int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            try {
                ProductFacade::stockUpdate($id, $request->validated());
                return new DataResource(Product::with('costs')->findOrFail($id));
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    public function productImageRemove(int $product_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::productImageRemove($product_id);
        }
    }

    public function delete($product_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $order = PosOrderFacade::getOrCreate();
            return ProductFacade::delete($product_id, $order->id);
        }
    }

    public function searchByCategory(int $category_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::searchByCategory($category_id);
        }
    }

    public function getCount()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::getCount();
        }
    }

    public function rolProducts()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::rolProducts();
        }
    }

    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Product/deleted');
        }
    }

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
                $query = Product::where('status', 0)->where('visibility', Product::VISIBILITY['visible'])->orderBy('created_at', 'desc')->onlyTrashed();
            } else {
                $query = Product::where('status', 0)->orderBy('created_at', 'desc')->onlyTrashed();
            }

            $payload = $query->where(function ($query) use ($request) {
                if (isset($request->search_product_code)) {
                    $query->where('code', 'like', '%' . $request->search_product_code . '%');
                }
                if (isset($request->search_product_name)) {
                    $query->where('name', 'like', '%' . $request->search_product_name . '%');
                }
                if (isset($request->search_product_selling_max) && isset($request->search_product_selling_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_selling_min));
                    $max = floatval(str_replace(",", "", $request->search_product_selling_max));
                    if ($max != 0) {
                        $query->where('selling', '>=', $min)->where('selling', '<=', $max);
                    } else {
                        $query->where('selling', '>=', $min);
                    }
                } else if (isset($request->search_product_selling_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_selling_min));
                    $query->where('selling', '>=', $min);
                } else if (isset($request->search_product_selling_max)) {
                    $max = floatval(str_replace(",", "", $request->search_product_selling_max));
                    if ($max != 0) {
                        $query->where('selling', '<=', $max);
                    }
                }
                if (isset($request->search_product_cost_max) && isset($request->search_product_cost_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_cost_min));
                    $max = floatval(str_replace(",", "", $request->search_product_cost_max));
                    if ($max != 0) {
                        $query->where('cost', '>=', $min)->where('cost', '<=', $max);
                    } else {
                        $query->where('cost', '>=', $min);
                    }
                } else if (isset($request->search_product_cost_min)) {
                    $min = floatval(str_replace(",", "", $request->search_product_cost_min));
                    $query->where('cost', '>=', $min);
                } else if (isset($request->search_product_cost_max)) {
                    $max = floatval(str_replace(",", "", $request->search_product_cost_max));
                    if ($max != 0) {
                        $query->where('cost', '<=', $max);
                    }
                }
                if (isset($request->search_order_stock)) {
                    if ($request->search_order_stock == 1) {
                        $query->where('product_type', 1);
                        $query->where(function ($query) {
                            $query->where('stock_quantity', '<=', 0)
                                ->orWhereNull('stock_quantity');
                        });
                    }
                    if ($request->search_order_stock == 2) {
                        $rol_products = $query->onlyTrashed()->get();
                        $filtered_products = [];
                        foreach ($rol_products as $rol_product) {
                            if (floatval($rol_product->rol) > 0 && floatval($rol_product->rol) >= floatval($rol_product->stock_quantity)) {
                                $filtered_products[] = $rol_product;
                            }
                        }
                        $query->whereIn('id', collect($filtered_products)->pluck('id'));
                    }
                }
            });
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['id', 'name'])->with('unit')
                ->paginate(request('per_page', config('basic.pagination_per_page')));
            return DataResource::collection($payload);
        }
    }

    public function restoreDeletedProduct($product_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::restoreDeletedProduct($product_id);
        }
    }

    /**
     * import
     *
     * @param  mixed $request
     * @return void
     */
    public function import(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $request->validate([
                'product_file' => 'required|mimes:xlsx'
            ]);

            Excel::import(new ProductsImport, $request->file('product_file'));
            $count_description = Session::get('imported');
            $errors = Session::get('import_errors');
            Session::forget('imported');
            Session::forget('import_errors');

            $response = [
                'message' => $count_description,
                'errors' => $errors
            ];

            return response()->json($response);
        }
    }

    /**
     * downloadSampleExcel
     *
     * @return void
     */
    public function downloadSampleExcel()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $file = public_path('sample_excel/products.xlsx');
            $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            // dd($file, $headers);
            return response()->download($file, 'products.xlsx', $headers);
        }
    }

    /**
     * GetLatestProduct
     * get the last saved product
     *
     * @return void
     */
    public function getSavedProduct()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return ProductFacade::getLastSavedProduct();
        }
    }

    /**
     * list
     * get all products from facade and return through GetProductResource
     *
     * @return void
     */
    public function list()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $products = ProductFacade::listInspector();
        } else {
            $products = ProductFacade::list();
        }
        return GetProductResource::collection($products);
        // }
    }

    public function stockableList()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $products = ProductFacade::stockableListInspector();
        } else {
            $products = ProductFacade::stockableList();
        }

        return GetProductResource::collection($products);
        // }
    }

    public function stockableListMultiselect(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ProductFacade::stockableListMultiselectInspector($request);
        } else {
            return ProductFacade::stockableListMultiselect($request);
        }
    }

    public function barcodePrint(Request $request)
    {
        $response['item'] = $request->all();
        $pdf = PDF::loadView('print.pages.barcode', $response)->setPaper([0, 0, 500, 250], 'portrait');

        return $pdf->stream("Barcode.pdf", array("Attachment" => false));
    }

    public function getProductByCode(Request $request)
    {
        $code = $request->query('code');

        $product = Product::where('code', $code)->first();

        return $product;
    }

    public function multiselectProductSearch(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ProductFacade::inspectorProductSearch($request);
        } else {
            return ProductFacade::multiselectProductSearch($request);
        }
    }
}
