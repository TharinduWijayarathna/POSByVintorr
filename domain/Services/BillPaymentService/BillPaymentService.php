<?php

namespace domain\Services\BillPaymentService;

use App\Models\BillPayment;
use App\Models\BusinessDetail;
use App\Models\Customer;
use App\Models\PosOrder;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * PosPaymentService
 * php version 8
 *
 * @category Service
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class BillPaymentService
{
    protected $bill_payment;

    protected $pos_order;

    protected $business_detail;

    protected $transaction;

    protected $customer;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->bill_payment = new BillPayment;
        $this->pos_order = new PosOrder;
        $this->business_detail = new BusinessDetail;
        $this->transaction = new Transaction;
        $this->customer = new Customer;
    }

    /**
     * Get
     * retrieve relevant data using pos_payment_id
     *
     * @param  int  $pos_payment_id
     * @return void
     */
    public function get(int $order_id)
    {
        return $this->bill_payment->where('order_id', $order_id)->first();
    }

    public function getInvoiceChartData()
    {
        return $this->pos_order->getInvoiceChartData();
    }

    public function getSalesChartData()
    {
        return $this->pos_order->getSalesChartData();
    }

    public function getTodayInvoiceChartData()
    {
        return $this->pos_order->getTodayInvoiceChartData();
    }

    public function getTodaySalesChartData()
    {
        return $this->pos_order->getTodaySalesChartData();
    }

    public function getTotalOutstanding()
    {
        $business_details = $this->business_detail->first();
        $totalOutstanding = $this->pos_order
            ->where('credit_status', 0)
            ->where('currency_id', $business_details->currency_id)
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere('status', 2);
            })
            ->sum(DB::raw('total - customer_paid'));

        return number_format($totalOutstanding, 2, '.', '');
    }

    public function getCurrencyWiseOutstanding()
    {

        $currencyWiseOutstanding = $this->pos_order
            ->whereNotNull('currency_id')
            ->where('credit_status', 0)
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere('status', 2);
            })
            ->groupBy('currency_id')
            ->selectRaw('currency_id, SUM(total - customer_paid) as outstanding')
            ->get();

        $result = '';

        foreach ($currencyWiseOutstanding as $row) {
            $result .= $row->currency_name.' '.number_format($row->outstanding, 2).', ';
        }

        // Remove the trailing comma and space
        $result = rtrim($result, ', ');

        return $result;
    }

    public function getBillTotalOutstanding()
    {
        $business_details = $this->business_detail->first();
        $totalOutstanding = $this->pos_order
            ->where('credit_status', 0)
            ->where('currency_id', $business_details->currency_id)
            ->where('type', 0)
            ->where('status', 1)
            ->sum(DB::raw('total - customer_paid'));

        return number_format($totalOutstanding, 2, '.', '');
    }

    public function deleteReceipt($receipt_id)
    {
        $today = \Carbon\Carbon::now();
        $receipt = $this->bill_payment->find($receipt_id);
        $pos_order = $this->pos_order->find($receipt->order_id);
        $customer = $this->customer->find($pos_order->customer_id);

        $pos_order->customer_paid = $pos_order->customer_paid - $receipt->accepted_amount;
        $pos_order->balance = 0;
        $pos_order->save();

        $transaction_data['code'] = $this->generateNewTRCode('TR');
        $transaction_data['date'] = $today;
        $transaction_data['reference_code'] = $pos_order->code;
        $transaction_data['payment_code'] = $receipt->code;
        if ($pos_order->customer_id != null) {
            $transaction_data['customer_id'] = $pos_order->customer_id;
            if ($customer) {
                $transaction_data['client'] = $customer->name;
            } else {
                $transaction_data['client'] = 'Customer not available';
            }
        }
        $transaction_data['currency_id'] = $pos_order->currency_id;
        $transaction_data['amount'] = $receipt->accepted_amount;
        $transaction_data['sign'] = 0;
        if ($pos_order->type == 1) {
            $transaction_data['type'] = 2;
            $transaction_data['description'] = 'Invoice Payment Delete';
        } elseif ($pos_order->type == 0) {
            $transaction_data['type'] = 1;
            $transaction_data['description'] = 'POS Credit Payment';
        }
        $this->transaction->create($transaction_data);

        return $receipt->delete();
    }

    private function generateNewTRCode($prefix)
    {
        $latest_student = $this->transaction->withTrashed()
            ->where('code', 'LIKE', $prefix.'%')
            ->orderBy('code', 'desc')
            ->first();

        if ($latest_student) {
            $latest_code = substr($latest_student->code, strlen($prefix));
            $next_num = intval($latest_code) + 1;
        } else {
            $next_num = 1;
        }

        $code = $prefix.sprintf('%05d', $next_num);

        return $code;
    }
}
