<?php

namespace domain\Services\ExpenseService;

use App\Jobs\SendSupplierExpenseMailJob\SendSupplierExpenseMailJob;
use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PosOrderItem;
use App\Models\Product;
use App\Models\ProductCost;
use App\Models\Stock;
use App\Models\StockLog;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Carbon\Carbon;
use DateTime;
use domain\Facades\ExpenseFacade\ExpenseFacade;
use domain\Facades\StockLogFacade\StockLogFacade;
use Illuminate\Support\Facades\Auth;
use PDF;

class ExpenseService
{
    protected $product;

    protected $expense;

    protected $cost;

    protected $order_item;

    protected $supplier;

    protected $transaction;

    protected $stock;

    protected $stock_log;

    protected $expense_category;

    private $transaction_balance;

    private $currency;

    private $business_details;

    public function __construct()
    {
        $this->product = new Product;
        $this->expense = new Expense;
        $this->cost = new ProductCost;
        $this->order_item = new PosOrderItem;
        $this->supplier = new Supplier;
        $this->transaction = new Transaction;
        $this->stock = new Stock;
        $this->stock_log = new StockLog;
        $this->expense_category = new ExpenseCategory;
        $this->transaction_balance = new TransactionBalance;
        $this->currency = new Currency;
        $this->business_details = new BusinessDetail;
    }

    /**
     * store
     *
     * @param  mixed  $data
     * @return void
     */
    public function store(array $data)
    {
        $data['created_by'] = Auth::user()->id;
        $data['created_at'] = $data['date'];
        if (isset($data['date'])) {
            $date = $data['date'];
            $dateObject = new DateTime($date);
            $today = $dateObject->format('Y-m-d');
            $data['date'] = $today;
        }

        $count = $this->expense->withTrashed()->count();

        $code = 'EX'.sprintf('%05d', $count + 1);
        $check = $this->expense->withTrashed()->where('code', $code)->first();

        while ($check) {
            $count++;
            $code = 'EX'.sprintf('%05d', $count);
            $check = $this->expense->withTrashed()->where('code', $code)->first();
        }

        $data['code'] = $code;

        $created_expense = $this->expense->create($data);
        $created_expense->created_at = $data['created_at'];
        $created_expense->save();

        // transaction log
        $transaction_count = $this->transaction->count();
        $tr_code = 'TR'.sprintf('%05d', $transaction_count + 1);
        $check_tr = $this->transaction->getCode($tr_code);

        while ($check_tr) {
            $transaction_count++;
            $tr_code = 'TR'.sprintf('%05d', $transaction_count);
            $check_tr = $this->transaction->getCode($tr_code);
        }

        $transaction_data['code'] = $tr_code;
        $transaction_data['type'] = 3;
        $transaction_data['date'] = $today;
        $transaction_data['reference_code'] = $created_expense->code;
        $transaction_data['payment_code'] = $created_expense->code;
        if (isset($data['supplier_id'])) {
            $supplier = $this->supplier->where('id', $data['supplier_id'])->first();
            $transaction_data['supplier_id'] = $data['supplier_id'];
            if ($supplier) {
                $transaction_data['client'] = $supplier->name;
            } else {
                $transaction_data['client'] = 'Supplier not available';
            }
        }
        $transaction_data['currency_id'] = $created_expense->currency_id;
        $transaction_data['amount'] = $created_expense->amount;
        $transaction_data['sign'] = 0;
        $transaction_data['description'] = 'Expense';
        if (isset($data['remark'])) {
            $transaction_data['remark'] = $created_expense->remark;
        }
        $this->transaction->create($transaction_data);

        $business_detail = BusinessDetail::first();
        $business_detail->transaction_balance = $business_detail->transaction_balance - $created_expense->amount;
        $business_detail->save();

        $transaction_balance = $this->transaction_balance->where('currency_id', $transaction_data['currency_id'])->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - $created_expense->amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = -$created_expense->amount;
            $this->transaction_balance->create($balance_data);
        }
        // end transaction log

        return $created_expense->id;
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

    public function categorySelectAll()
    {
        return $this->expense_category->orderBy('name', 'asc')->get();
    }

    public function get($id)
    {
        return $this->expense->withTrashed()->findOrFail($id);
    }

    public function getOrderItem($id, $order_id)
    {
        return $this->order_item->where('product_id', $id)->where('order_id', $order_id)->first();
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
        $dateString = $data['date'];
        $formattedDate = Carbon::parse($dateString);

        if (isset($data['date'])) {
            $data['date'] = $formattedDate;
            $data['created_at'] = $formattedDate;
        }
        $expense = $this->expense->findOrFail($id);

        // transaction log
        $created_transaction = $this->transaction->where('reference_code', $expense->code)->where('payment_code', $expense->code)->first();
        if ($created_transaction) {
            if (isset($data['supplier_id'])) {
                $supplier = $this->supplier->where('id', $data['supplier_id'])->first();
                $created_transaction->supplier_id = $data['supplier_id'];
                if ($supplier) {
                    $created_transaction->client = $supplier->name;
                } else {
                    $created_transaction->client = 'Supplier not available';
                }
            }

            $transaction_balance = $this->transaction_balance->where('currency_id', $data['currency_id'])->first();
            if ($transaction_balance) {
                if ($expense->currency_id == $data['currency_id']) {
                    $transaction_balance->amount = $transaction_balance->amount - ($data['amount'] - $created_transaction->amount);
                    $transaction_balance->save();
                } else {
                    $previous_transaction_balance = $this->transaction_balance->where('currency_id', $expense->currency_id)->first();
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + $expense->amount;
                    $previous_transaction_balance->save();
                    $transaction_balance->amount = $transaction_balance->amount - $data['amount'];
                    $transaction_balance->save();
                }
            } else {
                $previous_transaction_balance = $this->transaction_balance->where('currency_id', $expense->currency_id)->first();
                if ($expense->currency_id != $data['currency_id']) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + $expense->amount;
                    $previous_transaction_balance->save();
                }
                $currency = $this->currency->find($data['currency_id']);
                $balance_data['currency_id'] = $data['currency_id'];
                $balance_data['code'] = $currency['code'];
                $balance_data['amount'] = -$data['amount'];
                $this->transaction_balance->create($balance_data);
            }

            $created_transaction->currency_id = $data['currency_id'];

            $created_transaction->amount = $data['amount'];
            $created_transaction->save();
        }

        $expense->update($data);
        $expense->created_at = $expense->date;
        $expense->save();

        return $expense;
        // end transaction log
    }

    public function stockUpdate($id, $data)
    {
        $data['updated_by'] = Auth::user()->id;
        $product = $this->product->findOrFail($id);
        $today = \Carbon\Carbon::now();

        $data['product_id'] = $id;
        $data['created_by'] = Auth::id();
        $data['cost'] = $product->cost;
        $data['date'] = $today->toDateString();
        StockLogFacade::store($data);

        if ($data['transaction_type_id'] == 1) {
            $productData['stock_quantity'] = $product->stock_quantity + $data['quantity'];
        } else {
            $productData['stock_quantity'] = $product->stock_quantity - $data['quantity'];
        }
        $product->update($productData);
    }

    /**
     * Delete
     * delete specific data using pos_customer_id
     *
     * @param  int  $pos_customer_id
     * @return void
     */
    public function delete(int $expense_id)
    {
        // transaction log
        $expense = $this->expense->find($expense_id);
        $transaction = $this->transaction->where('reference_code', $expense->code)->first();

        $transaction_balance = $this->transaction_balance->where('currency_id', $expense->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount + $expense->amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($expense->currency_id);
            $balance_data['currency_id'] = $expense->currency_id;
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $expense->amount;
            $this->transaction_balance->create($balance_data);
        }

        $transaction->delete();

        return $expense->delete();
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

    public function all()
    {
        // return $this->product->get();
        return $this->product->orderByRaw('order_no IS NULL, order_no ASC')->get();
    }

    public function finishGoodByNameBarcode(string $name, int $product_category_id)
    {
        return $this->product->getFinishGoodByNameBarcode($name, $product_category_id);
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

    public function newCategory(array $data)
    {

        $expense_category = $this->expense_category->where('name', $data['name'])->first();
        if (! $expense_category) {

            return $this->expense_category->create($data);
        } else {
            return 'This category already exists';
        }
    }

    public function getCategory($id)
    {
        return $this->expense_category->findOrFail($id);
    }

    public function updateCategory($id, $data)
    {
        $category = $this->expense_category->findOrFail($id);

        return $category->update($data);
    }

    public function deleteCategory(int $product_id)
    {
        return $this->expense_category->find($product_id)->delete();
    }

    public function removeImage(int $id)
    {
        $details = $this->expense->find($id);
        $details->image_id = null;

        return $details->save();
    }

    public function getExpenseCategories()
    {
        return $this->expense_category->get();
    }

    public function getExpenseByPropsCategory($category_id, $currency_id)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        $currentMonthEnd = Carbon::now()->endOfMonth();

        $totalExpenses = $this->expense
            ->where('currency_id', $currency_id)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $categoryExpenses = $this->expense
            ->where('expense_category_id', $category_id)
            ->where('currency_id', $currency_id)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        if ($totalExpenses > 0) {
            $percentage = ($categoryExpenses / $totalExpenses) * 100;
        } else {
            $percentage = 0;
        }

        return $percentage;
    }

    public function getExpenseAmountByPropsCategory($category_id, $currency_id)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        $currentMonthEnd = Carbon::now()->endOfMonth();

        $categoryExpenses = $this->expense
            ->where('expense_category_id', $category_id)
            ->where('currency_id', $currency_id)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        return $categoryExpenses;
    }

    public function getExpenseAmountByPropsCurrencyId($currency_id)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        $currentMonthEnd = Carbon::now()->endOfMonth();

        $categoryExpenses = $this->expense
            ->where('type', 1)
            ->where('currency_id', $currency_id)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        return $categoryExpenses;
    }

    public function getExpenseByCategory($category_id = null, $currency_id = null, $monthly_id = null)
    {
        $query = $this->expense;

        if ($currency_id) {
            $query = $query->where('currency_id', $currency_id);
        }

        if ($monthly_id) {
            // Set the start and end dates for the selected month
            $currentMonthStart = Carbon::createFromDate(null, $monthly_id, 1)->startOfMonth();
            $currentMonthEnd = Carbon::createFromDate(null, $monthly_id, 1)->endOfMonth();
        } else {
            // Use the current month if no month is selected
            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd = Carbon::now()->endOfMonth();
        }

        $query = $query->whereBetween('date', [$currentMonthStart, $currentMonthEnd]);

        $totalExpenses = $query->sum('amount');

        if ($category_id) {
            $categoryExpenses = $query->where('expense_category_id', $category_id)->sum('amount');
        } else {
            $categoryExpenses = $totalExpenses;
        }

        if ($totalExpenses > 0) {
            $percentage = $categoryExpenses;
        } else {
            $percentage = 0;
        }

        return $percentage;
    }

    public function restoreExpense(int $expense_id)
    {
        $deleted_expense = $this->expense->withTrashed()->find($expense_id);
        $deleted_transaction = $this->transaction->withTrashed()->where('reference_code', $deleted_expense->code)->first();

        $transaction_balance = $this->transaction_balance->where('currency_id', $deleted_expense->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - $deleted_expense->amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($deleted_expense->currency_id);
            $balance_data['currency_id'] = $deleted_expense->currency_id;
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = -$deleted_expense->amount;
            $this->transaction_balance->create($balance_data);
        }

        $deleted_transaction->deleted_at = null;
        $deleted_transaction->save();
        $deleted_expense->deleted_at = null;

        return $deleted_expense->save();
    }

    public function deleteSupplierExpense(int $expense_id)
    {
        $expense = $this->expense->find($expense_id);
        $expense->supplier_id = null;

        return $expense->save();
    }

    public function sendSupplierExpenseEmail(int $expense_id, array $data)
    {
        try {
            $response['expense'] = ExpenseFacade::get($expense_id);
            $response['created_at'] = $response['expense']['created_at'];
            $response['print_type'] = 'expense';
            $response['config'] = $this->business_details->orderBy('id', 'desc')->first();
            $pdf = PDF::loadView('print.pages.Expense.expense', $response)->setPaper('a4');

            $pdfContent = $pdf->output();
            $filePath = storage_path('app/temp_expense.pdf');
            file_put_contents($filePath, $pdfContent);

            $sender_details = $this->business_details->first();

            $image = $sender_details->image_url;

            $default_mail = $data['to'];
            $sendData = [
                'sender_email' => $sender_details->email,
                'business_name' => $sender_details->name,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'cc' => $data['cc'],
            ];

            SendSupplierExpenseMailJob::dispatch($sendData, $default_mail, $filePath, $image);

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getPayrollByCurrency($currency_id, $monthly_id)
    {
        $query = $this->expense->where('type', 1);

        if ($currency_id) {
            $query = $query->where('currency_id', $currency_id);
        }

        if ($monthly_id) {
            $currentMonthStart = Carbon::createFromDate(null, $monthly_id, 1)->startOfMonth();
            $currentMonthEnd = Carbon::createFromDate(null, $monthly_id, 1)->endOfMonth();
        } else {
            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd = Carbon::now()->endOfMonth();
        }

        $query = $query->whereBetween('date', [$currentMonthStart, $currentMonthEnd]);

        $totalPayrolls = $query->sum('amount');

        return $totalPayrolls;
    }

    public function calculateTotals($expenses)
    {
        $totals = [
            'total' => 0,
        ];

        foreach ($expenses as $expense) {
            $totals['total'] += $expense['amount'];
        }

        return $totals;
    }
}
