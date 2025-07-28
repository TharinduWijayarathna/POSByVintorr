<?php

namespace domain\Services\ConfigurationService;

use App\Jobs\SendAccountResetTokenMailJob\SendAccountResetTokenMailJob;
use App\Models\AccountResetInfo;
use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\CustomerOutstandingEmail;
use App\Models\Expense;
use App\Models\InvoiceCost;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemFooterParameter;
use App\Models\InvoiceItemParameter;
use App\Models\InvoiceItemTax;
use App\Models\InvoicePayment;
use App\Models\MonthlyBusinessSummaryEmail;
use App\Models\Payment;
use App\Models\PosCustomer;
use App\Models\PosOrder;
use App\Models\PosOrderItem;
use App\Models\PosOrderTax;
use App\Models\Product;
use App\Models\ProductCost;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderItemFooterParameter;
use App\Models\PurchaseOrderItemParameter;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationItemFooterParameter;
use App\Models\QuotationItemParameter;
use App\Models\Receipt;
use App\Models\ReceiptRefund;
use App\Models\StockLog;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use App\Models\TransactionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ConfigurationService
{
    protected $business_detail;

    protected $pos_order;

    protected $customer_outstanding_email;

    protected $business_summary_email;

    protected $account_reset;

    public function __construct()
    {
        $this->business_detail = new BusinessDetail;
        $this->pos_order = new PosOrder;
        $this->customer_outstanding_email = new CustomerOutstandingEmail;
        $this->account_reset = new AccountResetInfo;
        $this->business_summary_email = new MonthlyBusinessSummaryEmail;
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

        return $this->business_detail->create($data);
    }

    public function update($data, $id)
    {
        $configuration = $this->business_detail->findOrFail($id);
        $configuration->update($data);
    }

    public function delete(int $id)
    {
        return $this->business_detail->find($id)->delete();
    }

    public function removeLogo(int $id)
    {
        $details = $this->business_detail->find($id);
        $details->image_id = null;
        $details->save();
    }

    public function removeBillLogo(int $id)
    {
        $details = $this->business_detail->find($id);
        $details->bill_image_id = null;
        $details->save();
    }

    public function removeInvoiceLogo(int $id)
    {
        $details = $this->business_detail->find($id);
        $details->invoice_image_id = null;
        $details->save();
    }

    public function removeBanner(int $id)
    {
        $details = $this->business_detail->find($id);
        $details->public_image_id = null;
        $details->save();
    }

    public function getDetails()
    {
        return $this->business_detail->first();
    }

    public function change($status)
    {
        $details = $this->business_detail->first();
        if ($status == 1) {
            $details->status = 1;
        } else {
            $details->status = 0;
        }

        return $details->save();
    }

    public function switchOff()
    {
        $details = $this->business_detail->first();
        $details->status = 0;

        return $details->save();
    }

    public function saveWeeklyTarget(int $weekly_target)
    {
        $details = $this->business_detail->first();
        $details->weekly_target = $weekly_target;

        return $details->save();
    }

    public function saveMonthlyTarget(int $monthly_target)
    {
        $details = $this->business_detail->first();
        $details->monthly_target = $monthly_target;

        return $details->save();
    }

    public function saveYearlyTarget(int $yearly_target)
    {
        $details = $this->business_detail->first();
        $details->yearly_target = $yearly_target;

        return $details->save();
    }

    public function getBaseCurrency()
    {
        $details = $this->business_detail->first();

        return $details->currency_name;
    }

    public function getWeeklyTarget()
    {
        $details = $this->business_detail->first();

        return $details->weekly_target;
    }

    public function getMonthlyTarget()
    {
        $details = $this->business_detail->first();

        return $details->monthly_target;
    }

    public function getYearlyTarget()
    {
        $details = $this->business_detail->first();

        return $details->yearly_target;
    }

    public function getTotalForThisWeek()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $business_details = $this->business_detail->first();

        $totalForThisWeek = $this->pos_order::where('status', 1)->where('currency_id', $business_details->currency_id)->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('total');

        return $totalForThisWeek;
    }

    public function getTotalForThisMonth()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $business_details = $this->business_detail->first();

        $totalForThisMonth = $this->pos_order::where('status', 1)->where('currency_id', $business_details->currency_id)->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('total');

        return $totalForThisMonth;
    }

    public function getTotalForThisYear()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $business_details = $this->business_detail->first();

        $totalForThisYear = $this->pos_order::where('status', 1)->where('currency_id', $business_details->currency_id)->whereBetween('date', [$startOfYear, $endOfYear])
            ->sum('total');

        return $totalForThisYear;
    }

    public function getPercentageForThisWeek()
    {
        $details = $this->business_detail->first();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $totalForThisWeek = $this->pos_order::where('status', 1)->where('currency_id', $details->currency_id)->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('total');

        if ($totalForThisWeek < $details->weekly_target) {
            return ($totalForThisWeek / $details->weekly_target) * 100;
        } else {
            return 100;
        }
    }

    public function getPercentageForThisMonth()
    {
        $details = $this->business_detail->first();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalForThisMonth = $this->pos_order::where('status', 1)->where('currency_id', $details->currency_id)->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('total');

        if ($totalForThisMonth < $details->monthly_target) {
            return ($totalForThisMonth / $details->monthly_target) * 100;
        } else {
            return 100;
        }
    }

    public function getPercentageForThisYear()
    {
        $details = $this->business_detail->first();

        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $totalForThisYear = $this->pos_order::where('status', 1)->where('currency_id', $details->currency_id)->whereBetween('date', [$startOfYear, $endOfYear])
            ->sum('total');

        if ($totalForThisYear < $details->yearly_target) {
            return ($totalForThisYear / $details->yearly_target) * 100;
        } else {
            return 100;
        }
    }

    public function storeDateValue($val)
    {
        $previous_value = $this->customer_outstanding_email->first();

        if ($previous_value !== null) {
            $previous_value->update([
                'value' => $val,
            ]);

            return $previous_value;
        } else {
            return $this->customer_outstanding_email->create([
                'value' => $val,
            ]);
        }
    }

    public function storeBusinessSummaryEmailDateValue($val)
    {
        $previous_value = $this->business_summary_email->first();

        if ($previous_value !== null) {
            $previous_value->update([
                'value' => $val,
            ]);

            return $previous_value;
        } else {
            return $this->business_summary_email->create([
                'value' => $val,
            ]);
        }
    }

    public function getDateValue()
    {
        $value = $this->customer_outstanding_email->first();

        return $value;
    }

    public function getMonthlyBusinessEmailDateValue()
    {
        $value = $this->business_summary_email->first();

        return $value;
    }

    public function sendTokenEmail()
    {

        $businessDetail = $this->business_detail->first();
        $email = $businessDetail->email;

        $resetToken = Str::random(8);
        if ($email != null) {

            $expirationDate = Carbon::now()->addMinutes(15);

            $data = [
                'email' => $email,
                'token' => $resetToken,
                'expiration_date' => $expirationDate,
                'is_send' => 1,
            ];

            // Account reset Requested mail
            Log::info($email);

            $this->account_reset->create($data);

            $sendData['resetToken'] = $resetToken;

            if (isset($businessDetail->name)) {
                $sendData['businessName'] = $businessDetail->name;
            }

            SendAccountResetTokenMailJob::dispatch($sendData, $email);
        }
    }

    public function resetAccount($token)
    {
        $latestToken = $this->account_reset->latest()->first();
        $latestToken->count++;
        $latestToken->save();

        try {
            if (! $latestToken) {
                return response()->json(['error' => 'No reset token found.'], 400);
            }

            if ($token !== $latestToken->token) {
                return response()->json(['error' => 'Invalid reset token.'], 400);
            }

            if ($latestToken->expiration_date <= Carbon::now()) {
                return response()->json(['error' => 'Reset token has expired.'], 400);
            }

            if ($latestToken->count >= 4) {
                return response()->json(['error' => 'Exceeded the maximum attempts!! Send reset token and try again'], 400);
            }

            if ($token === $latestToken->token && $latestToken->expiration_date > Carbon::now() && $latestToken->count <= 4) {
                Customer::truncate();
                BillPayment::truncate();
                Cart::truncate();
                CartItem::truncate();
                Expense::truncate();
                InvoiceCost::truncate();
                InvoiceItem::truncate();
                InvoiceItemParameter::truncate();
                InvoiceItemFooterParameter::truncate();
                InvoiceItemTax::truncate();
                InvoicePayment::truncate();
                Payment::truncate();
                PosCustomer::truncate();
                PosOrder::truncate();
                PosOrderItem::truncate();
                PosOrderTax::truncate();
                Product::truncate();
                ProductCost::truncate();
                PurchaseOrder::truncate();
                PurchaseOrderItem::truncate();
                PurchaseOrderItemParameter::truncate();
                PurchaseOrderItemFooterParameter::truncate();
                Quotation::truncate();
                QuotationItem::truncate();
                QuotationItemParameter::truncate();
                QuotationItemFooterParameter::truncate();
                Receipt::truncate();
                ReceiptRefund::truncate();
                StockLog::truncate();
                Supplier::truncate();
                Tax::truncate();
                Transaction::truncate();
                TransactionBalance::truncate();
                TransactionLog::truncate();

                return response()->json(['success' => 'Your account has been reset successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
