<?php

namespace App\Http\Controllers;

use App\Exports\Reports\ExpenseReportExport;
use App\Http\Requests\Expense\SendSupplierExpenseEmailRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Http\Requests\Category\CreateCategory;
use App\Http\Requests\Expense\CreateExpenseRequest;
use App\Models\ExpenseCategory;
use domain\Facades\PosOrderFacade\PosOrderFacade;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\DataResource;
use Spatie\QueryBuilder\QueryBuilder;
use domain\Facades\ImageFacade\ImageFacade;
use domain\Facades\ExpenseFacade\ExpenseFacade;
use App\Http\Requests\Product\UpdateStockRequest;
use App\Models\BusinessDetail;
use App\Models\Expense;
use Carbon\Carbon;
use domain\Facades\UserFacade\UserFacade;

class ExpenseController extends ParentController
{

    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Expense/index');
        }
    }

    public function create()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Expense/create');
        }
    }

    public function get(int $id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return ExpenseFacade::get($id);
        }
    }

    public function getOrderItem(int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $order = PosOrderFacade::getOrCreate();
            return ExpenseFacade::getOrderItem($id, $order->id);
        }
    }

    public function categoryIndex()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Expense/category');
        }
    }

    public function newCategory(CreateCategory $req)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::newCategory($req->all());
        }
    }

    public function categoryAll()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $query = ExpenseCategory::where('name', '!=', 'Other')->orderBy('name', 'asc');

            $payload = QueryBuilder::for($query)
                ->allowedSorts(['id', 'name'])
                ->paginate(request('per_page', config('basic.pagination_per_page')));

            return DataResource::collection($payload);
        }
    }

    public function categorySelectAll()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return ExpenseFacade::categorySelectAll();
        }
    }

    public function store(CreateExpenseRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            if ($request->has('image')) {
                $image = ImageFacade::store($request->file('image'));
                $request->merge(['image_id' => $image->id]);
            }
            return ExpenseFacade::store($request->all());
        }
    }

    public function cartAll()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::all();
        }
    }

    public function all(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Expense::query()->where('type', 0);

            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc');
                } else if ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc');
                } else if ($request->sorting_value == 3) {
                    $query->orderBy('amount', 'asc');
                } else if ($request->sorting_value == 4) {
                    $query->orderBy('amount', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            if (isset($request->search_code)) {
                $query->where('code', 'like', '%' . $request->search_code . '%');
            }
            if (isset($request->search_category)) {
                $query->where('expense_category_id', $request->search_category);
            }
            if (isset($request->search_supplier)) {
                $query->where('supplier_id', $request->search_supplier);
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

    public function search(Request $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            $payload = ExpenseFacade::search($request['params']['search']);
            return DataResource::collection($payload);
        }
    }

    public function getWithDetails(int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::getWithDetails($id);
        }
    }

    public function update(CreateExpenseRequest $request, int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            try {
                if ($request->has('image')) {
                    $image = ImageFacade::store($request->file('image'));
                    $request->merge(['image_id' => $image->id]);
                }
                return ExpenseFacade::update($id, $request->all());
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
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            try {
                ExpenseFacade::stockUpdate($id, $request->validated());
                return new DataResource(Product::with('costs')->findOrFail($id));
            } catch (\Throwable $th) {
                return response()->json([
                    // 'message' => 'Product update failed'
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    public function delete($expense_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::delete($expense_id);
        }
    }

    public function getCount()
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::getCount();
        }
    }

    public function getCategory($category_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::getCategory($category_id);
        }
    }

    public function updateCategory(CreateCategory $request, int $id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            try {
                ExpenseFacade::updateCategory($id, $request->validated());
            } catch (\Throwable $th) {
                return response()->json([
                    // 'message' => 'Product update failed'
                    'message' => $th->getMessage(),
                ], 422);
            }
        }
    }

    public function deleteCategory($category_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::deleteCategory($category_id);
        }
    }

    public function removeImage($id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::removeImage($id);
        }
    }

    public function loadExpense(int $expense_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            $response['expense_id'] = $expense_id;
            return Inertia::render('Expense/edit', $response);
        }
    }

    public function print(int $expense_id)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $response['expense'] = ExpenseFacade::get($expense_id);
            $response['created_at'] = $response['expense']['created_at'];
            $response['print_type'] = "expense";
            $response['config'] = BusinessDetail::orderBy('id', 'desc')->first();
            $pdf = PDF::loadView('print.pages.Expense.expense', $response)->setPaper('a4');
            return $pdf->stream("Payment.pdf", array("Attachment" => false));
        }
    }

    public function deletedList()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            // $response = UserFacade::retrieveHost();
            return Inertia::render('Expense/deleted');
        }
    }

    public function deletedAll(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $query = Expense::where('type', 0)->onlyTrashed();

            if (isset($request->sorting_value)) {
                if ($request->sorting_value == 1) {
                    $query->orderBy('code', 'desc');
                } else if ($request->sorting_value == 2) {
                    $query->orderBy('date', 'desc');
                } else if ($request->sorting_value == 3) {
                    $query->orderBy('amount', 'asc');
                } else if ($request->sorting_value == 4) {
                    $query->orderBy('amount', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            if (isset($request->search_code)) {
                $query->where('code', 'like', '%' . $request->search_code . '%');
            }
            if (isset($request->search_category)) {
                $query->where('expense_category_id', $request->search_category);
            }
            if (isset($request->search_supplier)) {
                $query->where('supplier_id', $request->search_supplier);
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

    public function restoreExpense($expense_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::restoreExpense($expense_id);
        }
    }

    public function deleteSupplierExpense($expense_id)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::deleteSupplierExpense($expense_id);
        }
    }

    public function downloadReceipt(Request $request)
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            $imagePath = $request->query('imagePath');
            $imageUrl = $imagePath;

            if (filter_var($imageUrl, FILTER_VALIDATE_URL) === false) {
                return response()->json(['error' => 'Invalid image URL'], 400);
            }

            $fileExtension = pathinfo($imageUrl, PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'pdf'])) {
                return response()->json(['error' => 'Unsupported image format'], 400);
            }

            $imageContent = file_get_contents($imageUrl);

            if ($imageContent === false) {
                return response()->json(['error' => 'Failed to fetch image'], 500);
            }

            return response($imageContent, 200)
                ->header('Content-Type', 'image/' . $fileExtension)
                ->header('Content-Disposition', 'attachment; filename="image.' . $fileExtension . '"');
        }
    }

    public function sendSupplierExpenseMail(int $expense_id, SendSupplierExpenseEmailRequest $request)
    {
        if (Auth::user()->user_role_id == User::USER_ROLE_ID['ADMIN'] || Auth::user()->user_role_id == User::USER_ROLE_ID['INSPECTOR']) {
            return ExpenseFacade::sendSupplierExpenseEmail($expense_id, $request->all());
        }
    }

    public function export(Request $request)
    {
        $code = $request->input('search_code');
        $search_category = $request->input('search_category');
        $search_supplier = $request->input('search_supplier');
        $search_order_from_date = $request->input('search_order_from_date');
        $search_order_to_date = $request->input('search_order_to_date');
        $currency = $request->input('currency');

        $expenses = $this-> buildExpenseQuery($request);

        if ($currency['id'] != 0) {
            $totals = ExpenseFacade::calculateTotals($expenses);
        } else {
            $totals = null;
        }

        $data = [
            'code' => $code,
            'category' => $search_category,
            'search_order_supplier' => $search_supplier,
            'search_order_from_date' => $search_order_from_date,
            'search_order_to_date' => $search_order_to_date,
            'expenses' => $expenses,
            'totals' => $totals,
            'currency' => $currency,
        ];

        $fileName = 'ExpenseReport-' . date('Y-m-d') . '-' . time() . '.xlsx';
        $filePath = 'exports/Reports/' . $fileName;
        $invoice_export = new ExpenseReportExport();
        Excel::store($invoice_export->export($data), $filePath, 'public');

        $path = asset('storage/' . $filePath);

        return response()->json(['path' => $path]);
    }

    private function buildExpenseQuery(Request $request)
    {
        $query = Expense::query()->where('type', 0);

        if (isset($request->sorting_value)) {
            if ($request->sorting_value == 1) {
                $query->orderBy('code', 'desc');
            } else if ($request->sorting_value == 2) {
                $query->orderBy('date', 'desc');
            } else if ($request->sorting_value == 3) {
                $query->orderBy('amount', 'asc');
            } else if ($request->sorting_value == 4) {
                $query->orderBy('amount', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (isset($request->search_code)) {
            $query->where('code', 'like', '%' . $request->search_code . '%');
        }
        if (isset($request->search_category)) {
            $query->where('expense_category_id', $request->search_category);
        }
        if (isset($request->search_supplier)) {
            $query->where('supplier_id', $request->search_supplier);
        }
        if (isset($request->search_date)) {
            $formattedDate = Carbon::parse($request->search_date)->toDateString();
            $query->where('date', $formattedDate);
        }
        if (isset($request->search_order_from_date)) {
            $query->whereDate('date', '>=', $request->search_order_from_date);
        }
        if (isset($request->search_order_to_date)) {
            $query->whereDate('date', '<=', $request->search_order_to_date);
        }
        if ($request->currency['id'] > 0) {
            $query->where('currency_id', $request->currency);
        }

        $payload = $query->get();
        return DataResource::collection($payload);
    }
}
