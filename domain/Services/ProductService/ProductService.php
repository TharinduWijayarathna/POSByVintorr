<?php

namespace domain\Services\ProductService;

use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\ProductCost;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\User;
use Carbon\Carbon;
use domain\Facades\StockLogFacade\StockLogFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    protected $product;

    protected $cost;

    protected $order_item;

    protected $stock;

    protected $stock_log;

    public function __construct()
    {
        $this->product = new Product;
        $this->cost = new ProductCost;
        $this->order_item = new PosOrderItem;
        $this->stock = new Stock;
        $this->stock_log = new StockLog;
    }

    /**
     * store
     *
     * @param  mixed  $data
     * @return void
     */
    public function store(array $data)
    {
        $product_data = null;

        if (isset($data['rol']) && $data['rol'] === null) {
            $data['rol'] = 0;
        }
        if (isset($data['order_no'])) {
            $product = $this->product->where('order_no', $data['order_no'])->first();

            if ($product) {
                return 'this priority number already exists';
            } else {
                $data['created_by'] = Auth::user()->id;

                // if (!array_key_exists('product_category_id', $data)) {
                //     $data['product_category_id'] = 1;
                // }

                $count = $this->product->withTrashed()->count();

                $code = 'P'.sprintf('%05d', $count + 1);
                $check = $this->product->withTrashed()->where('code', $code)->first();

                while ($check) {
                    $count++;
                    $code = 'P'.sprintf('%05d', $count);
                    $check = $this->product->withTrashed()->where('code', $code)->first();
                }

                $data['code'] = $code;

                $product_data = $this->product->create($data);

                $today = Carbon::now()->format('Y-m-d H:i:s');

                // Stock Log
                $stock_log_data['product_id'] = $product_data->id;
                $stock_log_data['product_name'] = $product_data->name;
                $stock_log_data['quantity'] = $product_data->stock_quantity ?? 0;
                $stock_log_data['balance'] = $product_data->stock_quantity ?? 0;
                $stock_log_data['cost'] = $product_data->cost ?? 0;
                $stock_log_data['selling_price'] = $product_data->selling ?? 0;
                $stock_log_data['reason'] = 'Open balance';
                $stock_log_data['type'] = StockLog::TYPE['plus'];
                $user = Auth::user();
                $stock_log_data['created_by'] = $user->id;
                $stock_log_data['created_user_name'] = $user->name;
                $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
                $stock_log_data['date'] = $today;
                if ($product_data->product_type == $this->product::PRODUCT_TYPE['stockable']) {
                    StockLogFacade::store($stock_log_data);
                }
            }
        } else {

            $data['created_by'] = Auth::user()->id;

            // if (!array_key_exists('product_category_id', $data)) {
            //     $data['product_category_id'] = 1;
            // }

            $count = $this->product->withTrashed()->count();

            $code = 'P'.sprintf('%05d', $count + 1);
            $check = $this->product->withTrashed()->where('code', $code)->first();

            while ($check) {
                $count++;
                $code = 'P'.sprintf('%05d', $count);
                $check = $this->product->withTrashed()->where('code', $code)->first();
            }

            $data['code'] = $code;

            $product_data = $this->product->create($data);

            $today = Carbon::now()->format('Y-m-d H:i:s');

            // Stock Log
            $stock_log_data['product_id'] = $product_data->id;
            $stock_log_data['product_name'] = $product_data->name;
            $stock_log_data['quantity'] = $product_data->stock_quantity ?? 0;
            $stock_log_data['balance'] = $product_data->stock_quantity ?? 0;
            $stock_log_data['cost'] = $product_data->cost ?? 0;
            $stock_log_data['selling_price'] = $product_data->selling ?? 0;
            $stock_log_data['reason'] = 'Open balance';
            $stock_log_data['type'] = StockLog::TYPE['plus'];
            $user = Auth::user();
            $stock_log_data['created_by'] = $user->id;
            $stock_log_data['created_user_name'] = $user->name;
            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
            $stock_log_data['date'] = $today;
            if ($product_data->product_type == $this->product::PRODUCT_TYPE['stockable']) {
                StockLogFacade::store($stock_log_data);
            }
        }

        return $product_data ? $product_data->id : null;
    }

    public function getNewCode()
    {
        $count = Product::where('status', 0)->withTrashed()->count();
        // Uniqu product code generator
        $code = sprintf('%05d', $count + 1);
        $check = Product::withTrashed()->where('code', $code)->first();
        while ($check) {
            $count++;
            $code = sprintf('%05d', $count);
            $check = Product::withTrashed()->where('code', $code)->first();
        }

        return $code;
    }

    public function getWithDetails($id)
    {
        return $this->product->findOrFail($id);
    }

    /**
     * show
     *
     * @param  mixed  $id
     * @return void
     */
    public function show($id)
    {
        return $this->product->findOrFail($id)->load('costs');
    }

    /**
     * get
     *
     * @param  mixed  $id
     * @return void
     */
    public function get($id)
    {
        return $this->product->find($id);
    }

    /**
     * getWithDeleted
     *
     * @param  mixed  $id
     * @return void
     */
    public function getWithDeleted($id)
    {
        return $this->product->withTrashed()->findOrFail($id);
    }

    public function getOrderItem($id, $order_id)
    {
        return $this->order_item->find($id);
    }

    public function getFirst()
    {
        return $this->product->get();
    }

    /**
     * update
     *
     * @param  mixed  $id
     * @param  mixed  $data
     * @return void
     */
    public function update($id, $data)
    {
        if (isset($data['order_no'])) {
            $product = $this->product->where('order_no', $data['order_no'])->first();

            if ($product) {
                if ($product->id != $id) {
                    return 'this priority number already exists';
                } else {
                    $data['updated_by'] = Auth::user()->id;
                    $product = $this->product->findOrFail($id);
                    if ($product->product_type != $data['product_type']) {
                        if ($data['product_type'] == $this->product::PRODUCT_TYPE['stockable']) {
                            // Stock Log
                            $stock_log_data['product_id'] = $product->id;
                            $stock_log_data['product_name'] = $product->name;
                            $stock_log_data['quantity'] = $product->stock_quantity ?? 0;
                            $stock_log_data['balance'] = $product->stock_quantity ?? 0;
                            $stock_log_data['cost'] = $product->cost ?? 0;
                            $stock_log_data['selling_price'] = $product->selling ?? 0;
                            $stock_log_data['reason'] = 'Product changed to stockable';
                            $stock_log_data['type'] = StockLog::TYPE['plus'];
                            $user = Auth::user();
                            $stock_log_data['created_by'] = $user->id;
                            $stock_log_data['created_user_name'] = $user->name;
                            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
                            $today = Carbon::now()->format('Y-m-d H:i:s');
                            $stock_log_data['date'] = $today;
                            StockLogFacade::store($stock_log_data);
                        }
                        if ($data['product_type'] == $this->product::PRODUCT_TYPE['non-stockable']) {
                            // Stock Log
                            $today = Carbon::now()->format('Y-m-d H:i:s');
                            $stock_log_data['product_id'] = $product->id;
                            $stock_log_data['product_name'] = $product->name;
                            $stock_log_data['quantity'] = $product->stock_quantity ?? 0;
                            $stock_log_data['balance'] = 0;
                            $stock_log_data['cost'] = $product->cost ?? 0;
                            $stock_log_data['selling_price'] = $product->selling ?? 0;
                            $stock_log_data['reason'] = 'Product changed to non-stockable';
                            $stock_log_data['type'] = StockLog::TYPE['minus'];
                            $user = Auth::user();
                            $stock_log_data['created_by'] = $user->id;
                            $stock_log_data['created_user_name'] = $user->name;
                            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
                            $stock_log_data['date'] = $today;
                            StockLogFacade::store($stock_log_data);
                        }
                    }
                    $product->update($data);
                }
            } else {
                $data['updated_by'] = Auth::user()->id;
                $product = $this->product->findOrFail($id);
                if ($product->product_type != $data['product_type']) {
                    if ($data['product_type'] == $this->product::PRODUCT_TYPE['stockable']) {
                        // Stock Log
                        $stock_log_data['product_id'] = $product->id;
                        $stock_log_data['product_name'] = $product->name;
                        $stock_log_data['quantity'] = $product->stock_quantity ?? 0;
                        $stock_log_data['balance'] = $product->stock_quantity ?? 0;
                        $stock_log_data['cost'] = $product->cost ?? 0;
                        $stock_log_data['selling_price'] = $product->selling ?? 0;
                        $stock_log_data['reason'] = 'Product changed to stockable';
                        $stock_log_data['type'] = StockLog::TYPE['plus'];
                        $user = Auth::user();
                        $stock_log_data['created_by'] = $user->id;
                        $stock_log_data['created_user_name'] = $user->name;
                        $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
                        $today = Carbon::now()->format('Y-m-d H:i:s');
                        $stock_log_data['date'] = $today;
                        StockLogFacade::store($stock_log_data);
                    }
                    if ($data['product_type'] == $this->product::PRODUCT_TYPE['non-stockable']) {
                        // Stock Log
                        $today = Carbon::now()->format('Y-m-d H:i:s');
                        $stock_log_data['product_id'] = $product->id;
                        $stock_log_data['product_name'] = $product->name;
                        $stock_log_data['quantity'] = $product->stock_quantity ?? 0;
                        $stock_log_data['balance'] = 0;
                        $stock_log_data['cost'] = $product->cost ?? 0;
                        $stock_log_data['selling_price'] = $product->selling ?? 0;
                        $stock_log_data['reason'] = 'Product changed to non-stockable';
                        $stock_log_data['type'] = StockLog::TYPE['minus'];
                        $user = Auth::user();
                        $stock_log_data['created_by'] = $user->id;
                        $stock_log_data['created_user_name'] = $user->name;
                        $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
                        $stock_log_data['date'] = $today;
                        StockLogFacade::store($stock_log_data);
                    }
                }
                $product->update($data);
            }
        } else {
            $data['updated_by'] = Auth::user()->id;
            $product = $this->product->findOrFail($id);

            if ($product->product_type != $data['product_type']) {
                if ($data['product_type'] == $this->product::PRODUCT_TYPE['stockable']) {
                    // Stock Log
                    $stock_log_data['product_id'] = $product->id;
                    $stock_log_data['product_name'] = $product->name;
                    $stock_log_data['quantity'] = $product->stock_quantity ?? 0;
                    $stock_log_data['balance'] = $product->stock_quantity ?? 0;
                    $stock_log_data['cost'] = $product->cost ?? 0;
                    $stock_log_data['selling_price'] = $product->selling ?? 0;
                    $stock_log_data['reason'] = 'Product changed to stockable';
                    $stock_log_data['type'] = StockLog::TYPE['plus'];
                    $user = Auth::user();
                    $stock_log_data['created_by'] = $user->id;
                    $stock_log_data['created_user_name'] = $user->name;
                    $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
                    $today = Carbon::now()->format('Y-m-d H:i:s');
                    $stock_log_data['date'] = $today;
                    StockLogFacade::store($stock_log_data);
                }
                if ($data['product_type'] == $this->product::PRODUCT_TYPE['non-stockable']) {
                    // Stock Log
                    $today = Carbon::now()->format('Y-m-d H:i:s');
                    $stock_log_data['product_id'] = $product->id;
                    $stock_log_data['product_name'] = $product->name;
                    $stock_log_data['quantity'] = $product->stock_quantity ?? 0;
                    $stock_log_data['balance'] = 0;
                    $stock_log_data['cost'] = $product->cost ?? 0;
                    $stock_log_data['selling_price'] = $product->selling ?? 0;
                    $stock_log_data['reason'] = 'Product changed to non-stockable';
                    $stock_log_data['type'] = StockLog::TYPE['minus'];
                    $user = Auth::user();
                    $stock_log_data['created_by'] = $user->id;
                    $stock_log_data['created_user_name'] = $user->name;
                    $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
                    $stock_log_data['date'] = $today;
                    StockLogFacade::store($stock_log_data);
                }
            }
            $product->update($data);
        }
    }

    public function stockUpdate($id, $data)
    {
        $data['updated_by'] = Auth::user()->id;
        $product = $this->product->findOrFail($id);
        $today = Carbon::now()->format('Y-m-d H:i:s');

        if ($data['transaction_type_id'] == 1) {
            $productData['stock_quantity'] = $product->stock_quantity + $data['quantity'];
        } else {
            $productData['stock_quantity'] = $product->stock_quantity - $data['quantity'];
        }

        $product->update($productData);
        $updated_product = $this->product->findOrFail($id);

        // Stock Log
        $stock_log_data['product_id'] = $updated_product->id;
        $stock_log_data['product_name'] = $updated_product->name;
        $stock_log_data['quantity'] = $data['quantity'] ?? 0;
        $stock_log_data['balance'] = $updated_product->stock_quantity ?? 0;
        $stock_log_data['cost'] = $updated_product->cost ?? 0;
        $stock_log_data['selling_price'] = $updated_product->selling ?? 0;
        if ($data['transaction_type_id'] == 1) {
            $stock_log_data['type'] = StockLog::TYPE['plus'];
            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
            $stock_log_data['reason'] = 'Stock In';
        } else {
            $stock_log_data['type'] = StockLog::TYPE['minus'];
            $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
            $stock_log_data['reason'] = 'Stock Out';
        }
        $user = Auth::user();
        $stock_log_data['created_by'] = $user->id;
        $stock_log_data['created_user_name'] = $user->name;
        $stock_log_data['date'] = $today;
        if (isset($data['remarks'])) {
            $stock_log_data['remarks'] = $data['remarks'];
        }
        StockLogFacade::store($stock_log_data);
    }

    public function productImageRemove(int $product_id)
    {
        $product = $this->product->find($product_id);
        $product->image_id = null;

        return $product->save();
    }

    /**
     * Delete
     * delete specific data using pos_customer_id
     *
     * @param  int  $pos_customer_id
     * @return void
     */
    public function delete(int $product_id, int $order_id)
    {
        $this->order_item->where('product_id', $product_id)->where('order_id', $order_id)->delete();
        $product = $this->product->find($product_id);

        // Stock Log
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $stock_log_data['product_id'] = $product->id;
        $stock_log_data['product_name'] = $product['name'];
        $stock_log_data['quantity'] = $product['stock_quantity'] ?? 0 ?? 0;
        $stock_log_data['balance'] = 0;
        $stock_log_data['cost'] = $product->cost ?? 0;
        $stock_log_data['selling_price'] = $product->selling ?? 0;
        $stock_log_data['reason'] = 'Product deleted';
        $stock_log_data['type'] = StockLog::TYPE['minus'];
        $user = Auth::user();
        $stock_log_data['created_by'] = $user->id;
        $stock_log_data['created_user_name'] = $user->name;
        $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_out'];
        $stock_log_data['date'] = $today;
        StockLogFacade::store($stock_log_data);

        return $product->delete();
    }

    public function addCost($id, $data)
    {
        $product = $this->product->findOrFail($id);

        $product->costs()->create([
            'expense_sub_category_id' => $data['expense_sub_category_id'],
            'expense_category_id' => $data['expense_category_id'],
            'supplier_id' => $data['supplier_id'],
            'amount' => $data['amount'],
            'quantity' => $data['quantity'],
            'remark' => $data['remark'],
        ]);
    }

    public function updateCost($id, $data)
    {

        $cost = $this->cost->findOrFail($id);
        $cost->update($data);
    }

    /**
     * destroy
     *
     * @param  mixed  $id
     * @return void
     */
    public function destroy($id)
    {
        $product = $this->product->findOrFail($id);
        $product->costs()->delete();

        return $product->delete();
    }

    public function deleteCost($id)
    {
        $cost = $this->cost->findOrFail($id);

        return $cost->delete();
    }

    public function getCost($id)
    {
        return $this->cost->findOrFail($id);
    }

    /**
     * status
     *
     * @param  mixed  $id
     * @return void
     */
    public function status($id)
    {
        $product = $this->product->findOrFail($id);
        if ($product->status == 0) {
            $product->status = 1;
        } else {
            $product->status = 0;
        }
        $product->update();

        return $product;
    }

    public function search($data)
    {
        return $this->product->search($data);
    }

    public function restoreProduct($id)
    {
        return $this->product->onlyTrashed()->where('id', $id)->restore();
    }

    public function permanentDelete($id)
    {
        $product = $this->product->withTrashed()->findOrFail($id);

        return $product->forceDelete();
    }

    public function getLatestProduct()
    {
        $count = $this->product->count();
        if ($count > 0) {
            $product = $this->product->latest('id')->first(['id', 'name', 'selling', 'introduction']);

            return $product;
        } else {
        }
    }

    public function getLimitedProducts()
    {
        return $this->product->orderByRaw('order_no IS NULL, order_no ASC')->limit(20)->get();
    }

    public function getInspectorLimitedProducts()
    {
        return $this->product->where('visibility', $this->product::VISIBILITY['visible'])->orderByRaw('order_no IS NULL, order_no ASC')->limit(20)->get();
    }

    public function all()
    {
        // return $this->product->get();
        return $this->product->orderByRaw('order_no IS NULL, order_no ASC')->limit(30)->get();
    }

    public function allForInspector()
    {
        // return $this->product->get();

        return $this->product
            ->orderByRaw('order_no IS NULL, order_no ASC')
            ->where('visibility', Product::VISIBILITY['visible'])
            ->where('stock_quantity', '>', 0)
            ->get();
    }

    public function searchByCategory($category_id)
    {
        return $this->product->where('product_category_id', $category_id)->get();
    }

    public function finishGoodByNameBarcode(string $name, int $product_category_id, int $order_id)
    {
        return $this->product->getFinishGoodByNameBarcode($name, $product_category_id, $order_id);
    }

    public function finishGoodByNameBarcodeInspector(string $name, int $product_category_id, int $order_id)
    {
        return $this->product->getFinishGoodByNameBarcodeInspector($name, $product_category_id, $order_id);
    }

    public function getById($product_id)
    {
        $product = $this->product->getById($product_id);

        return $product;
    }

    public function getCount()
    {
        $count = $this->product->where('stock_quantity', '>', 0)->count();

        return $count;
    }

    public function rolProducts()
    {

        $data = [];
        $count = 0;
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $rol_products = $this->product->where('visibility', $this->product::VISIBILITY['visible'])->get();
        } else {
            $rol_products = $this->product->get();
        }
        foreach ($rol_products as $rol_product) {
            if (floatval($rol_product->rol) > 0 && floatval($rol_product->rol) >= floatval($rol_product->stock_quantity)) {
                $data[] = $rol_product;
                $count++;
                if ($count >= 5) {
                    break;
                }
            }
        }

        return $data;
    }

    public function restoreDeletedProduct(int $product_id)
    {
        $deleted_product = $this->product->withTrashed()->find($product_id);
        $deleted_product->deleted_at = null;

        // Stock Log
        $today = Carbon::now()->format('Y-m-d H:i:s');
        $stock_log_data['product_id'] = $deleted_product->id;
        $stock_log_data['product_name'] = $deleted_product['name'];
        $stock_log_data['quantity'] = $deleted_product['stock_quantity'] ?? 0;
        $stock_log_data['balance'] = $deleted_product['stock_quantity'] ?? 0;
        $stock_log_data['cost'] = $deleted_product->cost ?? 0;
        $stock_log_data['selling_price'] = $deleted_product->selling ?? 0;
        $stock_log_data['reason'] = 'Restored deleted product';
        $stock_log_data['type'] = StockLog::TYPE['plus'];
        $user = Auth::user();
        $stock_log_data['created_by'] = $user->id;
        $stock_log_data['created_user_name'] = $user->name;
        $stock_log_data['transaction_type_id'] = StockLog::TRANSACTION_TYPE_ID['stock_in'];
        $stock_log_data['date'] = $today;
        StockLogFacade::store($stock_log_data);

        return $deleted_product->save();
    }

    public function getLastSavedProduct()
    {
        return $this->product->latest()->first();
    }

    public function list()
    {
        return $this->product->all();
    }

    public function listInspector()
    {
        return $this->product->where('visibility', $this->product::VISIBILITY['visible'])->get();
    }

    public function stockableList()
    {
        return $this->product->where('product_type', $this->product::PRODUCT_TYPE['stockable'])->limit(20)->get();
    }

    public function stockableListInspector()
    {
        return $this->product->where('product_type', $this->product::PRODUCT_TYPE['stockable'])->where('visibility', $this->product::VISIBILITY['visible'])->limit(20)->get();
    }

    public function stockableListMultiselect(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $products = $this->product->orderBy('name', 'asc')
                ->where('product_type', $this->product::PRODUCT_TYPE['stockable'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%'.$query.'%')
                        ->orWhere('code', 'like', '%'.$query.'%');
                });

            return $products->get();
        } else {
            return [];
        }
    }

    public function stockableListMultiselectInspector(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $products = $this->product->orderBy('name', 'asc')
                ->where('product_type', $this->product::PRODUCT_TYPE['stockable'])
                ->where('visibility', $this->product::VISIBILITY['visible'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%'.$query.'%')
                        ->orWhere('code', 'like', '%'.$query.'%');
                });

            return $products->get();
        } else {
            return [];
        }
    }

    public function multiselectProductSearch(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $products = $this->product->orderBy('name', 'asc')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%'.$query.'%')
                        ->orWhere('code', 'like', '%'.$query.'%');
                });

            return $products->get();
        } else {
            return [];
        }
    }

    public function inspectorProductSearch(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $products = $this->product->orderBy('name', 'asc')
                ->where('visibility', $this->product::VISIBILITY['visible'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%'.$query.'%')
                        ->orWhere('code', 'like', '%'.$query.'%');
                });

            return $products->get();
        } else {
            return [];
        }
    }
}
