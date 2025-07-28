<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Http\Requests\PosCustomer\CreatePosCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\DataResource;
use App\Imports\CustomerImport;
use App\Jobs\SendCustomerOutstandingMailJob\SendCustomerOutstandingMailJob;
use App\Models\BusinessDetail;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\Quotation;
use App\Models\User;
use Carbon\Carbon;
use domain\Facades\PosCustomerFacade\PosCustomerFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PosCustomerController extends ParentController
{
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Customer/index');
        }
    }

    public function store(CreatePosCustomerRequest $req)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::store($req->all());
        }
    }

    public function allStore(CreateCustomerRequest $req)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::allStore($req->all());
        }
    }

    public function getByNumber(int $contact)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::getByNumber($contact);
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Customer::orderBy('id', 'desc');
            if (isset($request->search_customer_name)) {
                $query->where('name', 'like', "%{$request->search_customer_name}%");
            }
            if (isset($request->search_customer_contact)) {
                $searchContact = $request->search_customer_contact;
                $query->where(function ($query) use ($searchContact) {
                    $query->where('contact', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact2', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact3', 'like', '%'.$searchContact.'%');
                });
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('name', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return CustomerResource::collection($payload);
        }
    }

    public function customerAll()
    {
        // if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
        return PosCustomerFacade::customerAll();
        // }
    }

    public function get($customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::get($customer_id);
        }
    }

    public function update(CreateCustomerRequest $req, $customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::update($req->all(), $customer_id);
        }
    }

    public function delete($customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::delete($customer_id);
        }
    }

    public function loadCustomer(int $customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            $response['customer_id'] = $customer_id;

            return Inertia::render('Customer/edit', $response);
        }
    }

    public function allInvoices(int $customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = PosOrder::query()->where('status', '>', 0)->where('status', '<', 4)->where('type', 1)->where('customer_id', $customer_id)->whereNotIn('status', [3])->orderBy('created_at', 'desc');
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('code', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function allQuotations(int $customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Quotation::query()->where('status', 1)->where('customer_id', $customer_id)->orderBy('updated_at', 'desc');
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('code', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function allBills(int $customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = PosOrder::query()->where('status', '>', 0)->where('status', '<', 4)->where('type', 0)->where('customer_id', $customer_id)->whereNotIn('status', [3])->orderBy('updated_at', 'desc');
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('code', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function dueAmounts(int $customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $orders = PosOrder::where('customer_id', $customer_id)
                ->where('credit_status', 0)
                ->whereNotIn('status', [2, 4, 5])
                ->get();

            $groups = [];

            foreach ($orders as $order) {
                $currencyId = $order->currency_id;

                if (! isset($groups[$currencyId])) {
                    $groups[$currencyId] = [
                        'currency_name' => $order->currency_name,
                        'due_amount' => 0,
                    ];
                }

                $groups[$currencyId]['due_amount'] += $order->total - $order->customer_paid;
            }

            $result = array_values($groups);

            return $result;
        }
    }

    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Customer/deleted');
        }
    }

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $query = Customer::orderBy('id', 'desc')->onlyTrashed();
            if (isset($request->search_customer_name)) {
                $query->where('name', 'like', "%{$request->search_customer_name}%");
            }
            if (isset($request->search_customer_contact)) {
                $searchContact = $request->search_customer_contact;
                $query->where(function ($query) use ($searchContact) {
                    $query->where('contact', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact2', 'like', '%'.$searchContact.'%');
                    $query->orWhere('contact3', 'like', '%'.$searchContact.'%');
                });
            }
            $payload = QueryBuilder::for($query)
                ->allowedSorts(['code'])
                ->allowedFilters(
                    AllowedFilter::callback('search', function ($query, $value) {
                        $query->Where('name', 'like', "%{$value}%");
                    })
                )
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return CustomerResource::collection($payload);
        }
    }

    public function restoreCustomer($customer_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::restoreCustomer($customer_id);
        }
    }

    public function multiselectCustomersSearch(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return PosCustomerFacade::multiselectCustomersSearch($request);
        }
    }

    /**
     * import
     *
     * @param  mixed  $request
     * @return void
     */
    public function import(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            $request->validate([
                'customer_file' => 'required|mimes:xlsx',
            ]);

            Excel::import(new CustomerImport, $request->file('customer_file'));
            $count_description = Session::get('imported');
            $errors = Session::get('import_errors');
            Session::forget('imported');
            Session::forget('import_errors');

            $response = [
                'message' => $count_description,
                'errors' => $errors,
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
            $file = public_path('sample_excel/customers.xlsx');
            $headers = ['Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            // dd($file, $headers);
            return response()->download($file, 'customer.xlsx', $headers);
        }
    }

    // send mail to customer
    public function sendCustomerOutstandingMail($customer_id, Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            try {
                $today = Carbon::now()->format('Y-m-d');

                $credit_invoices = PosOrder::where('credit_status', 0)
                    ->where('total', '>', 0)
                    ->where('type', 1)
                    ->where('customer_id', $customer_id)
                    ->where('status', '<', 4)
                    ->whereNotIn('status', [3])
                    ->where(function ($query) use ($today) {
                        $query->whereDate('due_date', $today)
                            ->orWhereNull('due_date');
                    })
                    ->get();

                $credit_bills = PosOrder::where('status', 1)
                    ->where('credit_status', 0)
                    ->where('is_return', 0)
                    ->where('type', 0)
                    ->where('customer_id', $customer_id)
                    ->get();

                if (count($credit_invoices) > 0 || count($credit_bills) > 0) {
                    $customer = Customer::find($customer_id);

                    if (isset($request->to)) {
                        $default_mail = $request->to;
                    } else {
                        if (isset($customer->email3)) {
                            $default_mail = $customer->email3;
                        } elseif (isset($customer->email2)) {
                            $default_mail = $customer->email2;
                        } elseif (isset($customer->email)) {
                            $default_mail = $customer->email;
                        }
                    }

                    $sender_details = BusinessDetail::first();

                    $image = $sender_details->image_url;

                    if (! isset($sender_details->email)) {
                        return response()->json(['message' => 'Check again your business email in settings section'], 500);
                    }

                    $sendData['sender_email'] = $sender_details->email;
                    $sendData['business_name'] = $sender_details->name;
                    $sendData['invoices'] = $credit_invoices;
                    $sendData['credit_bills'] = $credit_bills;
                    $sendData['subject'] = $request->subject;
                    $sendData['email'] = $default_mail;
                    $sendData['message'] = $request->message;
                    $sendData['cc'] = $request->cc;

                    SendCustomerOutstandingMailJob::dispatch($sendData, $sendData['email'], $image);

                    return response()->json(['message' => 'email send successfully']);
                } else {
                    return response()->json(['message' => 'No expiry outstanding balance found'], 500);
                }
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Failed to send email'], 500);
            }
        }
    }
}
