<?php

namespace App\Http\Controllers;

use App\Exports\Reports\PayrollReportExport;
use App\Http\Requests\Payroll\CreatePayrollRequest;
use App\Http\Requests\Payroll\SendEmployeePayrollEmailRequest;
use App\Http\Requests\Product\UpdateStockRequest;
use App\Http\Resources\DataResource;
use App\Models\BusinessDetail;
use App\Models\Expense;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use domain\Facades\ImageFacade\ImageFacade;
use domain\Facades\PayrollFacade\PayrollFacade;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use domain\Facades\UserFacade\UserFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Spatie\QueryBuilder\QueryBuilder;

class PayrollController extends ParentController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Payroll/index');
        }
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Payroll/create');
        }
    }

    /**
     * get
     *
     * @param  mixed  $id
     * @return void
     */
    public function get(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return PayrollFacade::get($id);
        }
    }

    /**
     * getOrderItem
     *
     * @param  mixed  $id
     * @return void
     */
    public function getOrderItem(int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = PosOrderFacade::getOrCreate();

            return PayrollFacade::getOrderItem($id, $order->id);
        }
    }

    /**
     * store
     *
     * @param  mixed  $request
     * @return void
     */
    public function store(CreatePayrollRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            if ($request->has('image')) {
                $image = ImageFacade::store($request->file('image'));
                $request->merge(['image_id' => $image->id]);
            }

            return PayrollFacade::store($request->all());
        }
    }

    /**
     * all
     *
     * @param  mixed  $request
     * @return void
     */
    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Expense::query()->where('type', 1);

            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc');
                } elseif ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc');
                } elseif ($request->sorting_value == 3) {
                    $query->orderBy('amount', 'asc');
                } elseif ($request->sorting_value == 4) {
                    $query->orderBy('amount', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            if (isset($request->search_code)) {
                $query->where('code', 'like', '%'.$request->search_code.'%');
            }
            if (isset($request->search_category)) {
                $query->where('payroll_category_id', $request->search_category);
            }
            if (isset($request->search_employee)) {
                $query->where('supplier_id', $request->search_employee);
            }
            if (isset($request->search_date)) {
                $formattedDate = Carbon::parse($request->search_date)->toDateString();
                $query->where('date', $formattedDate);
            }
            if (isset($request->search_from_date)) {
                $query->whereDate('date', '>=', $request->search_from_date);
            }
            if (isset($request->search_to_date)) {
                $query->whereDate('date', '<=', $request->search_to_date);
            }
            if ($request->currency > 0) {
                $query->where('currency_id', $request->currency);
            }

            $payload = QueryBuilder::for($query)
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    /**
     * search
     *
     * @param  mixed  $request
     * @return void
     */
    public function search(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $payload = PayrollFacade::search($request['params']['search']);

            return DataResource::collection($payload);
        }
    }

    /**
     * getWithDetails
     *
     * @param  mixed  $id
     * @return void
     */
    public function getWithDetails(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return PayrollFacade::getWithDetails($id);
        }
    }

    /**
     * update
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return void
     */
    public function update(CreatePayrollRequest $request, int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            try {
                if ($request->has('image')) {
                    $image = ImageFacade::store($request->file('image'));
                    $request->merge(['image_id' => $image->id]);
                }

                return PayrollFacade::update($id, $request->all());
            } catch (\Throwable $th) {
                return response()->json([
                    // 'message' => 'Product update failed'
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    /**
     * stockUpdate
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return void
     */
    public function stockUpdate(UpdateStockRequest $request, int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            try {
                PayrollFacade::stockUpdate($id, $request->validated());

                return new DataResource(Product::with('costs')->findOrFail($id));
            } catch (\Throwable $th) {
                return response()->json([
                    // 'message' => 'Product update failed'
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    /**
     * delete
     *
     * @param  mixed  $payroll_id
     * @return void
     */
    public function delete($payroll_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PayrollFacade::delete($payroll_id);
        }
    }

    /**
     * getCount
     *
     * @return void
     */
    public function getCount()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PayrollFacade::getCount();
        }
    }

    /**
     * removeImage
     *
     * @param  mixed  $id
     * @return void
     */
    public function removeImage($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PayrollFacade::removeImage($id);
        }
    }

    /**
     * loadPayroll
     *
     * @param  mixed  $payroll_id
     * @return void
     */
    public function loadPayroll(int $payroll_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            $response['payroll_id'] = $payroll_id;

            return Inertia::render('Payroll/edit', $response);
        }
    }

    /**
     * print
     *
     * @param  mixed  $payroll_id
     * @return void
     */
    public function print(int $payroll_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $response['payroll'] = PayrollFacade::get($payroll_id);
            $response['created_at'] = $response['payroll']['created_at'];
            $response['print_type'] = 'payroll';
            $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();
            $pdf = PDF::loadView('print.pages.Payroll.payroll', $response)->setPaper('a4');

            return $pdf->stream('Salary Payment.pdf', ['Attachment' => false]);
        }
    }

    /**
     * deletedList
     *
     * @return void
     */
    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Payroll/deleted');
        }
    }

    /**
     * deletedAll
     *
     * @param  mixed  $request
     * @return void
     */
    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Expense::where('type', 1)->onlyTrashed();

            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc');
                } elseif ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc');
                } elseif ($request->sorting_value == 3) {
                    $query->orderBy('amount', 'asc');
                } elseif ($request->sorting_value == 4) {
                    $query->orderBy('amount', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            if (isset($request->search_code)) {
                $query->where('code', 'like', '%'.$request->search_code.'%');
            }
            if (isset($request->search_category)) {
                $query->where('payroll_category_id', $request->search_category);
            }
            if (isset($request->search_employee)) {
                $query->where('supplier_id', $request->search_employee);
            }
            if (isset($request->search_date)) {
                $formattedDate = Carbon::parse($request->search_date)->toDateString();
                $query->where('date', $formattedDate);
            }
            if (isset($request->search_from_date)) {
                $query->whereDate('date', '>=', $request->search_from_date);
            }
            if (isset($request->search_to_date)) {
                $query->whereDate('date', '<=', $request->search_to_date);
            }
            if (isset($request->currency)) {
                $query->where('currency_id', $request->currency);
            }

            $payload = QueryBuilder::for($query)
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    /**
     * restorePayroll
     *
     * @param  mixed  $payroll_id
     * @return void
     */
    public function restorePayroll($payroll_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PayrollFacade::restorePayroll($payroll_id);
        }
    }

    /**
     * deleteEmployeePayroll
     *
     * @param  mixed  $payroll_id
     * @return void
     */
    public function deleteEmployeePayroll($payroll_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PayrollFacade::deleteEmployeePayroll($payroll_id);
        }
    }

    /**
     * downloadReceipt
     *
     * @param  mixed  $request
     * @return void
     */
    public function downloadReceipt(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $imagePath = $request->query('imagePath');
            $imageUrl = $imagePath;

            if (filter_var($imageUrl, FILTER_VALIDATE_URL) === false) {
                return response()->json(['error' => 'Invalid image URL'], 400);
            }

            $fileExtension = pathinfo($imageUrl, PATHINFO_EXTENSION);

            if (! in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'pdf'])) {
                return response()->json(['error' => 'Unsupported image format'], 400);
            }

            $imageContent = file_get_contents($imageUrl);

            if ($imageContent === false) {
                return response()->json(['error' => 'Failed to fetch image'], 500);
            }

            return response($imageContent, 200)
                ->header('Content-Type', 'image/'.$fileExtension)
                ->header('Content-Disposition', 'attachment; filename="image.'.$fileExtension.'"');
        }
    }

    /**
     * sendEmployeePayrollMail
     *
     * @param  mixed  $payroll_id
     * @param  mixed  $request
     * @return void
     */
    public function sendEmployeePayrollMail(int $payroll_id, SendEmployeePayrollEmailRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return PayrollFacade::sendEmployeePayrollEmail($payroll_id, $request->all());
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
        $search_from_date = $request->input('search_from_date');
        $search_to_date = $request->input('search_to_date');
        $currency = $request->input('search_currency');
        $code = $request->input('code');
        $employee = $request->input('employee');
        // $totals = [];

        // Build the order query
        $payrolls = $this->buildPayrollQuery($request);
        // $payrolls = PayrollFacade::calculateAgesAndTotals($payrollsQuery->get());

        // if currency is set calculate totals
        if ($currency) {
            $totals = PayrollFacade::calculateTotals($payrolls);
        }

        // Prepare the data to be passed to the Excel view
        $data = [
            'search_from_date' => $search_from_date,
            'search_to_date' => $search_to_date,
            'currency' => $currency,
            'payrolls' => $payrolls,
            'code' => $code,
            'employee' => $employee,
            'totals' => $totals,
        ];

        // Generate the Excel and store it in the storage then return the path
        $fileName = 'OutstandingReport-'.date('Y-m-d').'-'.time().'.xlsx';
        $filePath = 'exports/Reports/'.$fileName;
        $payroll_export = new PayrollReportExport;
        Excel::store($payroll_export->export($data), $filePath, 'public');

        // Generate the URL to the stored file
        $path = asset('storage/'.$filePath);

        return response()->json(['path' => $path]);
    }

    private function buildPayrollQuery(Request $request)
    {
        $query = Expense::query()->where('type', 1);

        if (isset($request->sorting_value)) {
            if ($request->sorting_value == 1) {
                $query->orderBy('code', 'desc');
            } elseif ($request->sorting_value == 2) {
                $query->orderBy('date', 'desc');
            } elseif ($request->sorting_value == 3) {
                $query->orderBy('amount', 'asc');
            } elseif ($request->sorting_value == 4) {
                $query->orderBy('amount', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (isset($request->code)) {
            $query->where('code', 'like', '%'.$request->code.'%');
        }
        if (isset($request->search_category)) {
            $query->where('payroll_category_id', $request->search_category);
        }
        if (isset($request->employee)) {
            $query->where('supplier_id', $request->employee);
        }
        if (isset($request->search_date)) {
            $formattedDate = Carbon::parse($request->search_date)->toDateString();
            $query->where('date', $formattedDate);
        }
        if (isset($request->search_from_date)) {
            $query->whereDate('date', '>=', $request->search_from_date);
        }
        if (isset($request->search_to_date)) {
            $query->whereDate('date', '<=', $request->search_to_date);
        }
        if ($request->search_currency['id'] > 0) {
            $query->where('currency_id', $request->search_currency);
        }

        $payload = $query->get();

        return DataResource::collection($payload);
    }
}
