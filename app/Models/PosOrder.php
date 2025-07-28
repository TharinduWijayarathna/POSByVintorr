<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use domain\Facades\TaxFacade\TaxFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS = [
        'PENDING' => 0,
        'DONE' => 1,
        'HOLD' => 2,
        'CANCEL' => 3,
        'RETURN' => 4,
        'ENQUIRE' => 5,
    ];

    const DISCOUNT_TYPE = [
        'AMOUNT' => 0,
        'PERCENTAGE' => 1,
    ];

    const IS_RETURN = [
        'NO' => 0,
        'YES' => 1,
    ];

    const CREDIT_STATUS = [
        'CREDIT' => 0,
        'PAID_UP' => 1,
    ];

    const TYPE = [
        'CART' => 0,
        'INVOICE' => 1,
    ];

    protected $fillable = [
        'created_by',
        'customer_type',
        'customer_id',
        'code',
        'status',
        'sub_total',
        'total',
        'discount',
        'discount_type',
        'sales_person_id',
        'customer_paid',
        'balance',
        'is_return',
        'remark',
        'discount_remark',
        'credit_status',
        'payment_type',
        'total_tax',
        'currency_id',
        'type',
        'date',
        'due_date',
        'note',
        'ref_no',
        'customer_name',
        'customer_address',
        'customer_email',
        'customer_mobile',
        'invoice_link',
    ];

    protected $appends = [
        'cashier_name',
        // 'date',
        'invoice_date',
        'formatted_sub_total',
        'formatted_total',
        'formatted_minus_total',
        'formatted_discount',
        'formatted_customer_paid',
        'formatted_credit',
        'formatted_due',
        'currency_name',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id')->withTrashed();
    }

    public function cashier()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getCashierNameAttribute()
    {
        return $this->cashier && $this->cashier->status == User::STATUS['VISIBLE'] ? $this->cashier->name : '';
    }

    public function getCode(string $code)
    {
        return $this->where('code', $code)->first();
    }

    public function getActiveOrder()
    {
        $order = $this->where('created_by', Auth::id())->where('status', 0)->where('is_return', 0)->where('type', 0)->first();

        $check_status = $this->where('created_by', Auth::id())->where('status', 3)->where('is_return', 0)->where('type', 0)->first();

        if ($order !== null) {
            return $order;
        } else if ($check_status !== null) {

            $check_zero_status = $this->where('created_by', Auth::id())->where('status', 0)->where('is_return', 0)->where('type', 0)->first();
            if ($check_zero_status === null) {
                $check_status->status = 0;
                $check_status->save();
                return $check_status;
            }
        }
    }

    public function getReturnOrder()
    {
        return $this->where('created_by', Auth::id())->where('status', 0)->where('is_return', 1)->first();
    }

    public function getInvoiceOrder()
    {
        return $this->where('created_by', Auth::id())->where('status', 0)->where('type', 1)->first();
    }

    public function getInvoiceOrderNew()
    {
        // $pending_invoice = $this->where('created_by', Auth::id())->where('status', 0)->where('type', 1)->first();
        $pending_invoice = $this->where('type', 1)->latest()->first();
        if ($pending_invoice) {
            $pending_invoice_items = PosOrderItem::where('order_id', $pending_invoice->id)->get();
            if ($pending_invoice->customer_id != null || !$pending_invoice_items->isEmpty()) {
                $pending_invoice->status = 1;
                $pending_invoice->save();
            } else if ($pending_invoice->created_by == Auth::id()) {
                return $pending_invoice;
            } else {
                // no pending invoice
            }
        }
    }

    /**
     * items
     * get item details by id
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(PosOrderItem::class, 'order_id', 'id');
    }


    /**
     * getCustomerTypeNameAttribute
     * get customer type name regarding current customer type
     *
     * @return void
     */
    public function getCustomerTypeNameAttribute()
    {
        return $this->customerTypeData ? $this->customerTypeData->name : 'N/A';
    }

    public function updateTotals($order_id, $subTotal)
    {
        $order = $this->where('id', $order_id)->withTrashed()->first();
        if ($order) {
            if ($order->discount_type == 1) {
                $percentage = ($order->discount * 100) / $order->sub_total;
                $order->discount = ($percentage / 100) * $subTotal;
                $order->total = $subTotal - $order->discount;
            }
            $order->sub_total = $subTotal;
            $order->save();

            $order1 = $this->where('id', $order_id)->withTrashed()->first();
            $order1->total = $order1->sub_total + $order1->total_tax - $order1->discount;
            if ($order1->total - $order1->customer_paid <= 0) {
                // $order1->balance = $order1->customer_paid - $order1->total;
                // $order1->credit_status = 1;
            } else {
                $order1->balance = 0;
                // $order1->credit_status = 0;
            }
            $order1->save();
        }
        return $order1;
    }

    /**
     * updateTaxes
     *
     * @param  mixed $order_id
     * @param  mixed $subTotal
     * @return void
     */
    public function updateTaxes($order_id, $tax_total)
    {
        $order = $this->where('id', $order_id)->withTrashed()->first();
        if ($order) {
            $order->total_tax = $tax_total;
            $order->save();
        }
        return $order;
    }

    // public function getDateAttribute()
    // {
    //     $createdAt = $this->updated_at;
    //     $carbon = \Carbon\Carbon::parse($createdAt);
    //     $date = $carbon->toDateTimeString();
    //     return $this->created_at ? $date : '-';
    // }

    public function getInvoiceDateAttribute()
    {
        $createdAt = $this->created_at;
        $carbon = \Carbon\Carbon::parse($createdAt);
        $date = $carbon->toDateTimeString();
        return $this->created_at ? $date : '-';
    }

    public function getFormattedSubTotalAttribute()
    {
        return number_format($this->sub_total, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount, 2);
    }

    public function getFormattedCustomerPaidAttribute()
    {
        return number_format($this->customer_paid, 2);
    }
    public function getFormattedCreditAttribute()
    {
        return number_format($this->balance, 2);
    }
    public function getFormattedDueAttribute()
    {
        return number_format(max($this->total - $this->customer_paid, 0), 2);
    }

    public function getFormattedMinusTotalAttribute()
    {
        $formattedTotal = number_format(abs($this->total), 2);
        return $formattedTotal;
    }

    public function getInvoiceChartData()
    {
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 1)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 2)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 3)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 4)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 5)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 6)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 7)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 8)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 9)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 10)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 11)->where('total', '>', 0)->where('status', 1)->count();
        // $data[] = $this->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', 12)->where('total', '>', 0)->where('status', 1)->count();

        // return $data;

        $data = [];

        $currencyIds = $this->whereYear('date', Carbon::now()->year)
            ->where('total', '>', 0)
            ->where('status', 1)
            ->whereNotNull('currency_id')
            ->distinct('currency_id')
            ->pluck('currency_id');

        foreach ($currencyIds as $currencyId) {
            $currency = Currency::find($currencyId);
            $currencyData = [
                'name' => $currency->code,
                'data' => []
            ];

            for ($month = 1; $month <= 12; $month++) {
                $totalInvoices = $this->whereYear('date', Carbon::now()->year)
                    ->whereMonth('date', $month)
                    ->where('total', '>', 0)
                    ->where('status', 1)
                    ->where('currency_id', $currencyId)
                    ->count();

                $currencyData['data'][] = $totalInvoices;
            }

            $data[] = $currencyData;
        }

        return $data;
    }

    public function getSalesChartData()
    {
        // $data = [];

        // for ($month = 1; $month <= 12; $month++) {
        //     $totalSales = $this->whereYear('updated_at', Carbon::now()->year)
        //         ->whereMonth('updated_at', $month)
        //         ->where('status', 1)
        //         ->sum('total');

        //     $data[] = $totalSales;
        // }

        // return $data;


        $data = [];

        $currencyIds = $this->whereYear('date', Carbon::now()->year)
            ->where('status', 1)
            ->whereNotNull('currency_id')
            ->distinct('currency_id')
            ->pluck('currency_id');

        foreach ($currencyIds as $currencyId) {
            $currency = Currency::find($currencyId);
            $currencyData = [
                'name' => $currency->code,
                'data' => []
            ];

            for ($month = 1; $month <= 12; $month++) {
                $totalSales = $this->whereYear('date', Carbon::now()->year)
                    ->whereMonth('date', $month)
                    ->where('status', 1)
                    ->where('currency_id', $currencyId)
                    ->sum('total');

                $currencyData['data'][] = $totalSales;
            }

            $data[] = $currencyData;
        }

        return $data;
    }

    public function getTodayInvoiceChartData()
    {

        // $data = [];

        // $currentYear = Carbon::now()->year;
        // $currentMonth = Carbon::now()->month;
        // $daysInMonth = Carbon::now()->daysInMonth;

        // for ($day = 1; $day <= $daysInMonth; $day++) {
        //     $rowCount = $this->whereYear('updated_at', $currentYear)
        //         ->whereMonth('updated_at', $currentMonth)
        //         ->whereDate('updated_at', $currentYear . '-' . $currentMonth . '-' . $day)
        //         ->where('total', '>', 0)
        //         ->where('status', 1)
        //         ->count();

        //     $data[] = $rowCount;
        // }

        // return $data;

        $data = [];

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $daysInMonth = Carbon::now()->daysInMonth;

        $currencyIds = $this->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('total', '>', 0)
            ->where('status', 1)
            ->whereNotNull('currency_id')
            ->distinct('currency_id')
            ->pluck('currency_id');

        foreach ($currencyIds as $currencyId) {
            $currency = Currency::find($currencyId);
            $currencyData = [
                'name' => $currency->code,
                'data' => []
            ];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $rowCount = $this->whereYear('date', $currentYear)
                    ->whereMonth('date', $currentMonth)
                    ->whereDate('date', $currentYear . '-' . $currentMonth . '-' . $day)
                    ->where('currency_id', $currencyId)
                    ->where('total', '>', 0)
                    ->where('status', 1)
                    ->count();

                $currencyData['data'][] = $rowCount;
            }

            $data[] = $currencyData;
        }

        return $data;
    }

    public function getTodaySalesChartData()
    {

        // $data = [];

        // $currentYear = Carbon::now()->year;
        // $currentMonth = Carbon::now()->month;
        // $daysInMonth = Carbon::now()->daysInMonth;

        // for ($day = 1; $day <= $daysInMonth; $day++) {
        //     $rowCount = $this->whereYear('updated_at', $currentYear)
        //         ->whereMonth('updated_at', $currentMonth)
        //         ->whereDate('updated_at', $currentYear . '-' . $currentMonth . '-' . $day)
        //         ->where('status', 1)
        //         ->sum('total');

        //     $data[] = $rowCount;
        // }

        // return $data;

        $data = [];

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $daysInMonth = Carbon::now()->daysInMonth;

        // Grouping sales data by currency_id where currency_id is not null
        $salesData = $this->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->whereNotNull('currency_id')
            ->where('status', 1)
            ->selectRaw('currency_id, SUM(total) as total')
            ->groupBy('currency_id')
            ->get();

        // Creating the data array
        foreach ($salesData as $sale) {
            $currencyId = $sale->currency_id;
            $currency = Currency::find($currencyId);
            $currencyData = [
                'name' => $currency->code,
                'data' => []
            ];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $rowCount = $this->whereYear('date', $currentYear)
                    ->whereMonth('date', $currentMonth)
                    ->whereDate('date', $currentYear . '-' . $currentMonth . '-' . $day)
                    ->where('currency_id', $currencyId)
                    ->where('status', 1)
                    ->sum('total');

                $currencyData['data'][] = $rowCount;
            }

            $data[] = $currencyData;
        }

        return $data;
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency->code ?? null;
    }

    /**
     * HeaderParameters
     *
     * @return void
     */
    public function headerParameters()
    {
        return $this->hasMany(InvoiceItemParameter::class, 'invoice_id', 'id');
    }
}
