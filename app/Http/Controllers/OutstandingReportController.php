<?php

namespace App\Http\Controllers;

use App\Exports\Reports\OutstandingReportExport;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\PosOrder;
use Carbon\Carbon;
use domain\Facades\OutstandingReportFacade\OutstandingReportFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OutstandingReportController extends ParentController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        // $response = UserFacade::retrieveHost();
        return Inertia::render('Reports/Outstanding/index');
    }

    /**
     * all
     *
     * @param  mixed  $request
     * @return void
     */
    public function all(Request $request)
    {
        $query = PosOrder::query()
            ->whereColumn('sub_total', '>', 'discount')
            ->where(function ($query) {
                // Due date is less than today
                $query->where('due_date', '<', Carbon::today())
                    ->orWhere(function ($query) {
                        // if due date is null consider date
                        $query->whereNull('due_date')
                            ->where(function ($query) {
                                // date is less than today
                                $query->where('date', '<=', Carbon::today())
                                    ->orWhere(function ($query) {
                                        // if date is null consider created_at
                                        $query->whereNull('date')
                                            ->where('created_at', '<', Carbon::today());
                                    });
                            });
                    });
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    // if credit status is 0 and customer paid is greater than 0
                    $query->where('credit_status', 0)
                        ->where('customer_paid', '>', 0);
                })
                    ->orWhere(function ($query) {
                        // if credit status is 1 and customer paid is less than total
                        $query->where('status', 1)
                            ->where('customer_paid', 0);
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->withoutTrashed();

        if (isset($request->search_order_from_date)) {
            $query->where(function ($query) use ($request) {
                // if date is greater than or equal to search date
                $query->where('date', '>=', $request->search_order_from_date)
                    ->orWhere(function ($query) use ($request) {
                        // if date is null consider created_at
                        $query->whereNull('date')
                            ->where('created_at', '>=', $request->search_order_from_date);
                    });
            });
        }
        if (isset($request->search_order_to_date)) {
            $query->where(function ($query) use ($request) {
                // if date is less than or equal to search date
                $query->where('date', '<=', $request->search_order_to_date)
                    ->orWhere(function ($query) use ($request) {
                        // if date is null consider created_at
                        $query->whereNull('date')
                            ->where('created_at', '<=', $request->search_order_to_date);
                    });
            });
        }

        if (isset($request->search_order_customer)) {
            if ($request->search_order_customer == 0) {
                // if customer id is 0 retrieve null customer
                $query->where('customer_id', 0);
            } else {
                // if customer is not null
                $query->where('customer_id', $request->search_order_customer);
            }
        }

        if ($request->search_order_currency > 0) {
            $query->where('currency_id', $request->search_order_currency);
        }

        $payload = QueryBuilder::for($query)
            // sort by code
            ->allowedSorts(['code'])
            ->allowedFilters(
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->Where('code', 'like', "%{$value}%");
                })
            )
            ->paginate(request('per_page', config('basic.pagination_per_page')));

        // calculate age range and totals
        $payload = OutstandingReportFacade::calculateAgesAndTotals($payload);

        return DataResource::collection($payload);
    }

    private function buildOrderQuery(Request $request)
    {
        $query = PosOrder::query()
            ->whereColumn('sub_total', '>', 'discount')
            ->where(function ($query) {
                $query->where('due_date', '<', Carbon::today())
                    ->orWhere(function ($query) {
                        $query->whereNull('due_date')
                            ->where(function ($query) {
                                $query->where('date', '<=', Carbon::today())
                                    ->orWhere(function ($query) {
                                        $query->whereNull('date')
                                            ->where('created_at', '<', Carbon::today());
                                    });
                            });
                    });
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('credit_status', 0)
                        ->where('customer_paid', '>', 0);
                })
                    ->orWhere(function ($query) {
                        $query->where('status', 1)
                            ->where('customer_paid', 0);
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->withoutTrashed();

        if (isset($request->search_order_from_date)) {
            $query->where(function ($query) use ($request) {
                $query->where('date', '>=', $request->search_order_from_date)
                    ->orWhere(function ($query) use ($request) {
                        $query->whereNull('date')
                            ->where('created_at', '>=', $request->search_order_from_date);
                    });
            });
        }

        if (isset($request->search_order_to_date)) {
            $query->where(function ($query) use ($request) {
                $query->where('date', '<=', $request->search_order_to_date)
                    ->orWhere(function ($query) use ($request) {
                        $query->whereNull('date')
                            ->where('created_at', '<=', $request->search_order_to_date);
                    });
            });
        }

        if (isset($request->customer)) {
            if ($request->customer == 0) {
                $query->whereNull('customer_id');
            } else {
                $query->where('customer_id', $request->customer);
            }
        }
        if ($request->search_order_currency['id'] > 0) {
            $query->where('currency_id', $request->search_order_currency);
        }

        if ($request->has('code') && ! empty($request->input('code'))) {
            $query->where('code', 'like', '%'.$request->input('code').'%');
        }

        return $query;
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
        $customer = $request->input('customer');
        $totals = [];

        // Get the business details
        $config = BusinessDetail::orderBy('id', 'desc')->first();

        // Build the order query
        $ordersQuery = $this->buildOrderQuery($request);
        $orders = OutstandingReportFacade::calculateAgesAndTotals($ordersQuery->get());

        // if currency is set calculate totals
        if ($currency) {
            $totals = OutstandingReportFacade::calculateTotals($orders);
        }

        // Prepare the data to be passed to the PDF view
        $data = [
            'search_order_from_date' => $search_order_from_date,
            'search_order_to_date' => $search_order_to_date,
            'currency' => $currency,
            'orders' => $orders,
            'code' => $code,
            'customer' => $customer,
            'config' => $config,
            'totals' => $totals,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('print.pages.Reports.OutstandingReport.report', $data);

        return $pdf->stream('OutstandingReport.pdf', ['Attachment' => false]);
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
        $customer = $request->input('customer');
        $totals = [];

        // Build the order query
        $ordersQuery = $this->buildOrderQuery($request);
        $orders = OutstandingReportFacade::calculateAgesAndTotals($ordersQuery->get());

        // if currency is set calculate totals
        if ($currency) {
            $totals = OutstandingReportFacade::calculateTotals($orders);
        }

        // Prepare the data to be passed to the Excel view
        $data = [
            'search_order_from_date' => $search_order_from_date,
            'search_order_to_date' => $search_order_to_date,
            'currency' => $currency,
            'orders' => $orders,
            'code' => $code,
            'customer' => $customer,
            'totals' => $totals,
            // 'config' => $config,
        ];

        // Generate the Excel and store it in the storage then return the path
        $fileName = 'OutstandingReport-'.date('Y-m-d').'-'.time().'.xlsx';
        $filePath = 'exports/Reports/'.$fileName;
        $outstanding_export = new OutstandingReportExport;
        Excel::store($outstanding_export->export($data), $filePath, 'public');

        // Generate the URL to the stored file
        $path = asset('storage/'.$filePath);

        return response()->json(['path' => $path]);
    }
}
