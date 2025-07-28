<?php

namespace domain\Services\PayrollService;

use App\Jobs\SendEmployeePayrollEmailJob\SendEmployeePayrollEmailJob;
use App\Models\BusinessDetail;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Carbon\Carbon;
use DateTime;
use domain\Facades\ExpenseFacade\ExpenseFacade;
use Illuminate\Support\Facades\Auth;
use PDF;

class PayrollService
{
    protected $payroll;
    protected $employee;
    protected $transaction;
    private $transaction_balance;
    private $currency;
    private $business_details;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->payroll = new Expense();
        $this->employee = new Supplier();
        $this->transaction = new Transaction();
        $this->transaction_balance = new TransactionBalance();
        $this->currency = new Currency();
        $this->business_details = new BusinessDetail();
    }


    /**
     * store
     *
     * @param  mixed $data
     * @return void
     */
    public function store(array $data)
    {
        $data['created_by'] = Auth::user()->id;
        $data['created_at'] = $data['date'];
        $data['supplier_id'] = $data['employee_id'];
        if (isset($data['date'])) {
            $date = $data['date'];
            $dateObject = new DateTime($date);
            $today = $dateObject->format('Y-m-d');
            $data['date'] = $today;
        }

        $this->payroll->where('type', 1)->withTrashed()->count();

        $code = $this->generateNewPayrollCode('SP');

        $data['code'] = $code;

        $created_payroll = $this->payroll->create($data);
        $created_payroll->created_at = $data['created_at'];
        $created_payroll->save();

        //transaction log
        $transaction_count = $this->transaction->count();
        $tr_code = $this->generateNewTransactionCode('TR');

        $transaction_data['type'] = 3;
        $transaction_data['date'] = $today;
        $transaction_data['reference_code'] = $created_payroll->code;
        $transaction_data['payment_code'] = $created_payroll->code;
        if (isset($data['employee_id'])) {
            $employee = $this->employee->find($data['employee_id']);
            $transaction_data['supplier_id'] = $data['employee_id'];
            if ($employee) {
                $transaction_data['client'] = $employee->name;
            } else {
                $transaction_data['client'] = "Employee not available";
            }
        }
        $transaction_data['currency_id'] = $created_payroll->currency_id;
        $transaction_data['amount'] = $created_payroll->amount;
        $transaction_data['sign'] = 0;
        $transaction_data['description'] = "Salary Payment";
        $this->transaction->create($transaction_data);

        $business_detail = BusinessDetail::first();
        $business_detail->transaction_balance = $business_detail->transaction_balance - $created_payroll->amount;
        $business_detail->save();

        $transaction_balance = $this->transaction_balance->where('currency_id', $transaction_data['currency_id'])->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - $created_payroll->amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($transaction_data['currency_id']);
            $balance_data['currency_id'] = $transaction_data['currency_id'];
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = -$created_payroll->amount;
            $this->transaction_balance->create($balance_data);
        }
        //end transaction log

        return $created_payroll->id;
    }

    /**
     * generateNewPayrollCode
     *
     * @param  mixed $prefix
     * @return void
     */
    private function generateNewPayrollCode($prefix)
    {
        // Fetch the latest record that starts with the prefix
        $latest_payroll = $this->payroll->where('type', 1)->withTrashed()
            ->where('code', 'LIKE', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($latest_payroll) {
            // Extract the numeric part and increment it
            $latest_code = substr($latest_payroll->code, strlen($prefix));
            $next_num = intval($latest_code) + 1;
        } else {
            // No records found, start from 1
            $next_num = 1;
        }

        // Generate the new code
        $code = $prefix . sprintf('%05d', $next_num);

        return $code;
    }

    /**
     * generateNewTransactionCode
     *
     * @param  mixed $prefix
     * @return void
     */
    private function generateNewTransactionCode($prefix)
    {
        // Fetch the latest record that starts with the prefix
        $latest_transaction = $this->transaction->where('type', 1)->withTrashed()
            ->where('code', 'LIKE', $prefix . '%')
            ->latest()
            ->first();

        if ($latest_transaction) {
            // Extract the numeric part and increment it
            $latest_code = substr($latest_transaction->code, strlen($prefix));
            $next_num = intval($latest_code) + 1;
        } else {
            // No records found, start from 1
            $next_num = 1;
        }

        // Generate the new code
        $code = $prefix . sprintf('%05d', $next_num);

        return $code;
    }


    /**
     * get
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        return $this->payroll->where('type', 1)->withTrashed()->findOrFail($id);
    }

    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */

    public function update($id, $data)
    {
        $dateString = $data['date'];
        $formattedDate = Carbon::parse($dateString);
        $data['supplier_id'] = $data['employee_id'];
        if (isset($data['date'])) {
            $data['date'] = $formattedDate;
            $data['created_at'] = $formattedDate;
        }
        $payroll = $this->payroll->findOrFail($id);

        //transaction log
        $created_transaction = $this->transaction->where('reference_code', $payroll->code)->where('payment_code', $payroll->code)->first();
        if ($created_transaction) {
            if (isset($data['employee_id'])) {
                $employee = $this->employee->where('id', $data['employee_id'])->first();
                $created_transaction->supplier_id = $data['employee_id'];
                if ($employee) {
                    $created_transaction->client = $employee->name;
                } else {
                    $created_transaction->client = "Employee not available";
                }
            }

            $transaction_balance = $this->transaction_balance->where('currency_id', $data['currency_id'])->first();
            if ($transaction_balance) {
                if ($payroll->currency_id == $data['currency_id']) {
                    $transaction_balance->amount = $transaction_balance->amount - ($data['amount'] - $created_transaction->amount);
                    $transaction_balance->save();
                } else {
                    $previous_transaction_balance = $this->transaction_balance->where('currency_id', $payroll->currency_id)->first();
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + $payroll->amount;
                    $previous_transaction_balance->save();
                    $transaction_balance->amount = $transaction_balance->amount - $data['amount'];
                    $transaction_balance->save();
                }
            } else {
                $previous_transaction_balance = $this->transaction_balance->where('currency_id', $payroll->currency_id)->first();
                if ($payroll->currency_id != $data['currency_id']) {
                    $previous_transaction_balance->amount = $previous_transaction_balance->amount + $payroll->amount;
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

        $payroll->update($data);
        $payroll->created_at = $payroll->date;
        $payroll->save();
        return $payroll;
        //end transaction log
    }


    /**
     * Delete
     * delete specific data using pos_customer_id
     *
     * @param  int   $pos_customer_id
     * @return void
     */
    public function delete(int $payroll_id)
    {
        //transaction log
        $payroll = $this->payroll->find($payroll_id);
        $transaction = $this->transaction->where('reference_code', $payroll->code)->first();

        $transaction_balance = $this->transaction_balance->where('currency_id', $payroll->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount + $payroll->amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($payroll->currency_id);
            $balance_data['currency_id'] = $payroll->currency_id;
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = $payroll->amount;
            $this->transaction_balance->create($balance_data);
        }

        $transaction->delete();
        return $payroll->delete();
    }

    /**
     * removeImage
     *
     * @param  mixed $id
     * @return void
     */
    public function removeImage(int $id)
    {
        $details = $this->payroll->find($id);
        $details->image_id = null;
        return $details->save();
    }


    /**
     * getExpenseByPropsCategory
     *
     * @param  mixed $category_id
     * @param  mixed $currency_id
     * @return void
     */
    public function getExpenseByPropsCategory($category_id, $currency_id)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        $currentMonthEnd = Carbon::now()->endOfMonth();

        $totalExpenses = $this->payroll
            ->where('currency_id', $currency_id)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $categoryExpenses = $this->payroll
            ->where('payroll_category_id', $category_id)
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

    /**
     * getExpenseAmountByPropsCategory
     *
     * @param  mixed $category_id
     * @param  mixed $currency_id
     * @return void
     */
    public function getExpenseAmountByPropsCategory($category_id, $currency_id)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        $currentMonthEnd = Carbon::now()->endOfMonth();

        $categoryExpenses = $this->payroll
            ->where('payroll_category_id', $category_id)
            ->where('currency_id', $currency_id)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        return $categoryExpenses;
    }

    /**
     * getExpenseByCategory
     *
     * @param  mixed $category_id
     * @param  mixed $currency_id
     * @param  mixed $monthly_id
     * @return void
     */
    public function getExpenseByCategory($category_id = null, $currency_id = null, $monthly_id = null)
    {
        $query = $this->payroll;

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

        $query = $query->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd]);

        $totalExpenses = $query->sum('amount');

        if ($category_id) {
            $categoryExpenses = $query->where('payroll_category_id', $category_id)->sum('amount');
        } else {
            $categoryExpenses = $totalExpenses;
        }

        if ($totalExpenses > 0) {
            $percentage = ($categoryExpenses / $totalExpenses) * 100;
        } else {
            $percentage = 0;
        }

        return $percentage;
    }

    /**
     * restorePayroll
     *
     * @param  mixed $payroll_id
     * @return void
     */
    public function restorePayroll(int $payroll_id)
    {
        $deleted_payroll = $this->payroll->where('type', 1)->withTrashed()->find($payroll_id);
        $deleted_transaction = $this->transaction->withTrashed()->where('reference_code', $deleted_payroll->code)->first();

        $transaction_balance = $this->transaction_balance->where('currency_id', $deleted_payroll->currency_id)->first();
        if ($transaction_balance) {
            $transaction_balance->amount = $transaction_balance->amount - $deleted_payroll->amount;
            $transaction_balance->save();
        } else {
            $currency = $this->currency->find($deleted_payroll->currency_id);
            $balance_data['currency_id'] = $deleted_payroll->currency_id;
            $balance_data['code'] = $currency['code'];
            $balance_data['amount'] = -$deleted_payroll->amount;
            $this->transaction_balance->create($balance_data);
        }

        $deleted_transaction->deleted_at = null;
        $deleted_transaction->save();
        $deleted_payroll->deleted_at = null;
        return $deleted_payroll->save();
    }

    /**
     * deleteEmployeePayroll
     *
     * @param  mixed $payroll_id
     * @return void
     */
    public function deleteEmployeePayroll(int $payroll_id)
    {
        $payroll = $this->payroll->find($payroll_id);
        $payroll->supplier_id = null;
        return $payroll->save();
    }

    /**
     * sendEmployeePayrollEmail
     *
     * @param  mixed $payroll_id
     * @param  mixed $data
     * @return void
     */
    public function sendEmployeePayrollEmail(int $payroll_id, array $data)
    {
        try {
            $response['payroll'] = ExpenseFacade::get($payroll_id);
            $response['created_at'] = $response['payroll']['created_at'];
            $response['print_type'] = "payroll";
            $response['config'] = $this->business_details->orderBy('id', 'desc')->first();
            $pdf = PDF::loadView('print.pages.Payroll.payroll', $response)->setPaper('a4');

            $pdfContent = $pdf->output();
            $filePath = storage_path('app/temp_payroll.pdf');
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

            SendEmployeePayrollEmailJob::dispatch($sendData, $default_mail, $filePath, $image);
            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function calculateTotals($payrolls)
    {
        // Initialize totals array
        $totals = [
            'total' => 0
        ];

        // Calculate totals
        foreach ($payrolls as $payroll) {
            // Calculate total
            $totals['total'] += $payroll['amount'];
        }

        return $totals;
    }
}
