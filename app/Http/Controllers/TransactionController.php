<?php

namespace App\Http\Controllers;

use App\Exports\Reports\TransactionReportExport;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Resources\DataResource;
use App\Models\Transaction;
use App\Models\User;
use domain\Facades\TransactionFacade\TransactionFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionController extends ParentController
{
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Transaction/index');
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Transaction::query()->orderByRaw('date DESC, created_at DESC');
            if (isset($request->search_from_date)) {
                $query->whereDate('date', '>=', $request->search_from_date);
            }
            if (isset($request->search_to_date)) {
                $query->whereDate('date', '<=', $request->search_to_date);
            }
            if (isset($request->search_type)) {
                if ($request->search_type > 0) {
                    $query->where('type', $request->search_type);
                }
            }
            if (isset($request->search_transaction_type)) {
                if ($request->search_transaction_type == 1) {
                    $query->where('sign', 1);
                }
                if ($request->search_transaction_type == 2) {
                    $query->where('sign', 0);
                }
            }
            if (isset($request->search_ref_code)) {
                $query->where('reference_code', 'like', "%{$request->search_ref_code}%");
            }
            if (isset($request->search_client)) {
                $query->where('client', 'like', "%{$request->search_client}%");
            }
            if ($request->currency > 0) {
                $query->where('currency_id', $request->currency);
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function store(CreateTransactionRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return TransactionFacade::store($request->all());
        }
    }

    public function edit(int $transaction_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return TransactionFacade::edit($transaction_id);
        }
    }

    public function update(CreateTransactionRequest $request, int $transaction_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return TransactionFacade::update($request->all(), $transaction_id);
        }
    }

    public function delete(int $transaction_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return TransactionFacade::delete($transaction_id);
        }
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
        $search_type = $request->input('search_type');
        $search_transaction_type = $request->input('search_transaction_type');
        $search_from_date = $request->input('search_from_date');
        $search_to_date = $request->input('search_to_date');
        $currency = $request->input('search_currency');
        $code = $request->input('code');
        $client = $request->input('client');
        // $totals = [];

        // Build the order query
        $transactions = $this->buildTransactionQuery($request);

        // if currency is set calculate totals
        if ($currency) {
            $totals = TransactionFacade::calculateTotals($transactions);
        }

        // Prepare the data to be passed to the Excel view
        $data = [
            'type' => $search_type,
            'search_transaction_type' => $search_transaction_type,
            'search_from_date' => $search_from_date,
            'search_to_date' => $search_to_date,
            'currency' => $currency,
            'transactions' => $transactions,
            'code' => $code,
            'client' => $client,
            'totals' => $totals,
        ];

        // Generate the Excel and store it in the storage then return the path
        $fileName = 'TransactionReport-'.date('Y-m-d').'-'.time().'.xlsx';
        $filePath = 'exports/Reports/'.$fileName;
        $transaction_export = new TransactionReportExport;
        Excel::store($transaction_export->export($data), $filePath, 'public');

        // Generate the URL to the stored file
        $path = asset('storage/'.$filePath);

        return response()->json(['path' => $path]);
    }

    private function buildTransactionQuery(Request $request)
    {
        $query = Transaction::query()->orderByRaw('date DESC, created_at DESC');
        if (isset($request->search_from_date)) {
            $query->whereDate('date', '>=', $request->search_from_date);
        }
        if (isset($request->search_to_date)) {
            $query->whereDate('date', '<=', $request->search_to_date);
        }
        if (isset($request->search_type)) {
            if ($request->search_type > 0) {
                $query->where('type', $request->search_type);
            }
        }
        if (isset($request->search_transaction_type)) {
            if ($request->search_transaction_type == 1) {
                $query->where('sign', 1);
            }
            if ($request->search_transaction_type == 2) {
                $query->where('sign', 0);
            }
        }
        if (isset($request->code)) {
            $query->where('reference_code', 'like', "%{$request->code}%");
        }
        if (isset($request->client)) {
            $query->where('client', 'like', "%{$request->client}%");
        }
        if ($request->search_currency['id'] > 0) {
            $query->where('currency_id', $request->search_currency);
        }

        $payload = $query->get();

        return DataResource::collection($payload);
    }
}
