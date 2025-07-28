<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CONVERT_STATUS = [
        'QUOTATION' => 0,
        'INVOICE' => 1,
    ];

    protected $fillable = [
        'code',
        'created_by',
        'customer_id',
        'sub_total',
        'total',
        'total_tax',
        'discount',
        'discount_type',
        'currency_id',
        'note',
        'ref_no',
        'status',
        'quotation_status',
        'date',
        'customer_name',
        'customer_address',
        'customer_email',
        'customer_mobile',
        'quotation_link'
    ];

    protected $appends = [
        'cashier_name',
        'quotation_date',
        'formatted_sub_total',
        'formatted_total',
        'formatted_tax_total',
        'currency_name',
    ];


    /**
     * items
     * get item details by id
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'id');
    }

    public function getCode(string $code)
    {
        return $this->where('code', $code)->first();
    }

    public function getQuotationOrder()
    {
        return $this->where('created_by', Auth::id())->where('status', 0)->first();
    }

    public function getQuotationOrderNew()
    {
        $pending_quotation = $this->latest()->first();
        if ($pending_quotation) {
            $pending_quotation_items = QuotationItem::where('quotation_id', $pending_quotation->id)->get();
            if ($pending_quotation->customer_id != null || !$pending_quotation_items->isEmpty()) {
                $pending_quotation->status = 1;
                $pending_quotation->save();
            } else if ($pending_quotation->created_by == Auth::id()) {
                return $pending_quotation;
            } else {
                // no pending invoice
            }
        }
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function cashier()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getCashierNameAttribute()
    {
        return $this->cashier && $this->cashier->status == User::STATUS['VISIBLE'] ? $this->cashier->name : '';
    }

    public function getFormattedSubTotalAttribute()
    {
        return number_format($this->sub_total, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getFormattedTaxTotalAttribute()
    {
        return number_format($this->total_tax, 2);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency->code ?? null;
    }

    public function updateTotals($quotation_id, $subTotal)
    {
        $quotation = $this->where('id', $quotation_id)->withTrashed()->first();
        if ($quotation) {
            $quotation->sub_total = $subTotal;
            $quotation->save();

            $quotation1 = $this->where('id', $quotation_id)->withTrashed()->first();
            $quotation1->total = $quotation1->sub_total + $quotation1->total_tax - $quotation1->discount;
            $quotation1->save();
        }
        return $quotation1;
    }

    /**
     * updateTaxes
     *
     * @param  mixed $quotation_id
     * @param  mixed $subTotal
     * @return void
     */
    public function updateTaxes($quotation_id, $tax_total)
    {
        $quotation = $this->where('id', $quotation_id)->withTrashed()->first();
        if ($quotation) {
            $quotation->total_tax = $tax_total;
            $quotation->save();
        }
        return $quotation;
    }

    public function getQuotationDateAttribute()
    {
        $createdAt = $this->created_at;
        $carbon = \Carbon\Carbon::parse($createdAt);
        $date = $carbon->toDateTimeString();
        if ($this->date != null) {
            return $this->date;
        } else {
            return $this->created_at;
        }
    }

    /**
     * HeaderParameters
     *
     * @return void
     */
    public function headerParameters()
    {
        return $this->hasMany(QuotationItemParameter::class, 'quotation_id', 'id');
    }
}
