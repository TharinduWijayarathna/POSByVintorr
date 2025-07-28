<?php

namespace App\Http\Controllers;

use App\Exports\Reports\TaxReportExport;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\PosOrder;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaxReportController extends Controller
{
    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Reports/TaxReport/index');
    }

    /**
     * all
     *
     * @return void
     */
    public function all(Request $request)
    {
        $query = PosOrder::where('sub_total', '>', 0)->orderBy('created_at', 'desc');

        if (isset($request->search_order_type)) {
            $order_type = $request->search_order_type;
            if ($order_type == 1) {
                $query->where('type', $order_type - 1);
            }
            if ($order_type == 2) {
                $query->where('type', $order_type - 1);
            }
        }

        if (isset($request->search_order_from_date)) {
            $query->whereDate('date', '>=', $request->search_order_from_date);
        }

        if (isset($request->search_order_to_date)) {
            $query->whereDate('date', '<=', $request->search_order_to_date);
        }

        if ($request->search_order_currency != '0') {
            $query->where('currency_id', $request->search_order_currency);
        }

        $total_tax_sum = $query->clone()->sum('total_tax');

        $payload = QueryBuilder::for($query)
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->Where('code', 'like', "%{$value}%");
                })
            )->paginate(request('per_page', config('basic.pagination_per_page')));

        return DataResource::collection($payload)->additional([
            'total_tax_sum' => $total_tax_sum,
        ]);
    }

    /**
     * print
     *
     * @param  mixed  $request
     * @return void
     */
    public function print(Request $request)
    {

        // Validate the request
        $search_order_from_date = $request->input('search_order_from_date');
        $search_order_to_date = $request->input('search_order_to_date', now()->toDateString());
        $currency = $request->input('search_order_currency');
        $code = $request->input('code');
        $order_type = $request->input('search_order_type') == 1 ? 'POS'
            : ($request->input('search_order_type') == 2 ? 'Invoice' : null);

        // Get the business details
        $config = BusinessDetail::orderBy('id', 'desc')->first();

        // Build the order query
        $orders = $this->buildOrderQuery($request);

        // Calculate the total tax sum
        $total_tax_sum = $orders->sum('total_tax');

        // Prepare the data to be passed to the PDF view
        $data = [
            'search_order_from_date' => $search_order_from_date,
            'search_order_to_date' => $search_order_to_date,
            'currency' => $currency,
            'orders' => $orders,
            'order_type' => $order_type,
            'code' => $code,
            'config' => $config,
            'total_tax_sum' => $total_tax_sum,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('print.pages.Reports.TaxReport.report', $data);

        return $pdf->stream('TaxReport.pdf', ['Attachment' => false]);
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
        $search_order_from_date = $request->input('search_order_from_date');
        $search_order_to_date = $request->input('search_order_to_date', now()->toDateString());
        $currency = $request->input('search_order_currency');
        $code = $request->input('code');
        $order_type = $request->input('search_order_type') == 1 ? 'POS'
            : ($request->input('search_order_type') == 2 ? 'Invoice' : null);

        // Build the order query
        $orders = $this->buildOrderQuery($request);

        // Calculate the total tax sum
        $total_tax_sum = $orders->sum('total_tax');

        // Prepare the data to be passed to the Excel view
        $data = [
            'search_order_from_date' => $search_order_from_date,
            'search_order_to_date' => $search_order_to_date,
            'currency' => $currency,
            'orders' => $orders,
            'order_type' => $order_type,
            'code' => $code,
            // 'config' => $config,
            'total_tax_sum' => $total_tax_sum,
        ];

        // Generate the Excel and store it in the storage then return the path
        $fileName = 'TaxReport-'.date('Y-m-d').'-'.time().'.xlsx';
        $filePath = 'exports/Reports/'.$fileName;
        $order_export = new TaxReportExport;
        Excel::store($order_export->export($data), $filePath, 'public');

        // Generate the URL to the stored file
        $path = asset('storage/'.$filePath);

        return response()->json(['path' => $path]);
    }

    /**
     * buildOrderQuery
     *
     * @param  mixed  $request
     * @return void
     */
    private function buildOrderQuery($request)
    {
        $query = PosOrder::where('sub_total', '>', 0)->orderBy('created_at', 'desc');

        if (isset($request->search_order_type)) {
            $order_type = $request->search_order_type;
            if ($order_type == 1) {
                $query->where('type', $order_type - 1);
            }
            if ($order_type == 2) {
                $query->where('type', $order_type - 1);
            }
        }

        if (isset($request->search_order_from_date)) {
            $query->whereDate('date', '>=', $request->search_order_from_date);
        }

        if (isset($request->search_order_to_date)) {
            $query->whereDate('date', '<=', $request->search_order_to_date);
        }

        if ($request->search_order_currency != '0') {
            $query->where('currency_id', $request->search_order_currency);
        }

        if ($request->code) {
            $query->where('code', 'like', "%{$request->code}%");
        }

        $queryBuilder = QueryBuilder::for($query);

        return $queryBuilder->get();
    }
}
