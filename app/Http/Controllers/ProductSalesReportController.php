<?php

namespace App\Http\Controllers;

use App\Exports\Reports\ProductSalesReportExport;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\PosOrder;
use App\Models\Product;
use App\Models\User;
use domain\Facades\ProductSalesFacade\ProductSalesFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductSalesReportController extends ParentController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Reports/ProductSales/index');
    }

    /**
     * all
     *
     * @param  mixed  $request
     * @return void
     */
    public function all(Request $request)
    {
        // Check if any filters are set
        $filtersSet = $request->has('product_id') || $request->has('search_product_from_date') || $request->has('search_product_to_date') || $request->has('status');

        if (! $filtersSet) {
            // Return an empty collection or an appropriate response if no filters are set
            return DataResource::collection(collect());
        }

        // Query for PosOrder
        $query = PosOrder::query()->orderBy('id', 'desc');

        // Apply search_product_from_date filter if provided
        if ($request->has('search_product_from_date')) {
            $query->whereDate('date', '>=', $request->search_product_from_date);
        }

        // Apply search_product_to_date filter if provided
        if ($request->has('search_product_to_date')) {
            $query->whereDate('date', '<=', $request->search_product_to_date);
        }

        //  Apply currency filter if provided
        if ($request->search_order_currency != '0') {
            $query->where('currency_id', $request->search_order_currency);
        }

        // Get the IDs of the filtered PosOrders
        $orderIds = $query->pluck('id');

        // Query PosOrderItems related to the filtered orders
        $queryItems = ProductSalesFacade::getOrderItems($orderIds);

        // Join the product table to apply the visibility filter
        $queryItems->join('products', 'pos_order_items.product_id', '=', 'products.id');

        // Apply visibility filter based on user role
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $queryItems->where('products.visibility', Product::VISIBILITY['visible']);
        }

        // Apply other filters
        if ($request->has('search_product_id')) {
            $queryItems->where('pos_order_items.product_id', $request->search_product_id);
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'AVAILABLE') {
                $queryItems->whereNull('products.deleted_at');
            } elseif ($status === 'DELETED') {
                $queryItems->whereNotNull('products.deleted_at');
            }
        }

        $queryBuilder = QueryBuilder::for($queryItems);

        if ($request->has('search')) {
            $queryBuilder->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where('product_id', 'like', "%{$value}%");
                })
            );
        }

        if ($request->has('sort')) {
            $queryBuilder->allowedSorts(['product_id', 'total']);
        }

        $payload = $queryBuilder->paginate(request('per_page', config('basic.pagination_per_page')));

        return DataResource::collection($payload);
    }

    public function buildProductsQuery(Request $request)
    {

        // Query for PosOrder
        $query = PosOrder::query()->orderBy('id', 'desc');

        // Apply search_product_from_date filter if provided
        if ($request->has('search_product_from_date')) {
            $query->whereDate('date', '>=', $request->search_product_from_date);
        }

        // Apply search_product_to_date filter if provided
        if ($request->has('search_product_to_date')) {
            $query->whereDate('date', '<=', $request->search_product_to_date);
        }

        //  Apply currency filter if provided
        if ($request->search_product_currency['id'] != '0') {
            $query->where('currency_id', $request->search_product_currency['id']);
        }

        // Get the IDs of the filtered PosOrders
        $orderIds = $query->pluck('id');

        // Query PosOrderItems related to the filtered orders
        $queryItems = ProductSalesFacade::getOrderItems($orderIds);

        // Apply product_id filter if provided
        if (isset($request->product['id'])) {
            $queryItems->where('product_id', $request->product['id']);
        }

        // Apply status filter if provided
        if (isset($request->status['id'])) {
            $status = $request->input('status');

            $queryItems->whereHas('product', function ($query) use ($status) {
                if ($status['name'] === 'AVAILABLE') {
                    $query->whereNull('deleted_at');
                } elseif ($status['name'] === 'DELETED') {
                    $query->whereNotNull('deleted_at');
                }
            });
        }

        if (Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $queryItems->whereHas('product', function ($query) {
                $query->where('visibility', Product::VISIBILITY['visible']);
            });
        }

        $queryBuilder = QueryBuilder::for($queryItems);

        if ($request->has('search')) {
            $queryBuilder->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where('product_id', 'like', "%{$value}%");
                })
            );
        }

        if ($request->has('sort')) {
            $queryBuilder->allowedSorts(['product_id', 'total']);
        }

        $results = $queryBuilder->get();

        return DataResource::collection($results);
    }

    /**
     * print
     *
     * @param  mixed  $request
     * @return void
     */
    public function print(Request $request)
    {
        $product_items = $this->buildProductsQuery($request)->collection->toArray();

        // Validate the request
        $search_product_from_date = $request->input('search_product_from_date', now()->toDateString());
        $search_product_to_date = $request->input('search_product_to_date', now()->toDateString());

        // Get the business details
        $config = BusinessDetail::orderBy('id', 'desc')->first();

        // Calculate the total amount
        $total = array_sum(array_column($product_items, 'total_amount'));

        // Prepare the data to be passed to the PDF view
        $data = [
            'search_product_from_date' => $search_product_from_date,
            'search_product_to_date' => $search_product_to_date,
            'product_items' => $product_items,
            'total' => $total,
            'config' => $config,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('print.pages.Reports.ProductSalesReport.report', $data);

        return $pdf->stream('ProductSalesReport.pdf', ['Attachment' => false]);
    }

    /**
     * export
     *
     * @param  mixed  $request
     * @return void
     */
    public function export(Request $request)
    {
        // Validate the request
        $search_product_from_date = $request->input('search_product_from_date', now()->toDateString());
        $search_product_to_date = $request->input('search_product_to_date', now()->toDateString());
        $status = $request->input('status');
        $currency = $request->input('search_product_currency');
        $product = $request->input('product');
        // $product_items = $request->input('product_items');

        $product_items = $this->buildProductsQuery($request)->collection->toArray();

        // Calculate the total amount
        $total = array_sum(array_column($product_items, 'total_amount'));

        // Prepare the data to be passed to the Excel view
        $data = [
            'search_product_from_date' => $search_product_from_date,
            'search_product_to_date' => $search_product_to_date,
            'product_items' => $product_items,
            'currency' => $currency,
            'product' => $product,
            'status' => $status,
            'total' => $total,
            // 'config' => $config,
        ];

        // Generate the Excel and store it in the storage then return the path
        $fileName = 'ProductSalesReport-'.date('Y-m-d').'-'.time().'.xlsx';
        $filePath = 'exports/Reports/'.$fileName;
        $product_export = new ProductSalesReportExport;
        Excel::store($product_export->export($data), $filePath, 'public');

        // Generate the URL to the stored file
        $path = asset('storage/'.$filePath);

        return response()->json(['path' => $path]);
    }
}
