<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
        'purchase_order_status',
        'code',
        'created_by',
        'supplier_id',
        'total',
        'currency_id',
        'note',
        'ref_no',
        'date',
        'supplier_name',
        'supplier_address',
        'supplier_phone',
        'supplier_email',
        'purchase_order_link',
    ];

    protected $appends = [
        'purchase_order_items',
        'cashier_name',
        'purchase_order_date',
        'formatted_total',
        'currency_name',
    ];

    /**
     * items
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id', 'id');
    }

    /**
     * getPurchaseOrderItemsAttribute
     *
     * @return void
     */
    public function getPurchaseOrderItemsAttribute()
    {
        return $this->items;
    }

    /**
     * getPurchaseOrder
     *
     * @return void
     */
    public function getPurchaseOrder()
    {
        return $this->where('created_by', Auth::id())->where('status', 0)->first();
    }

    public function getPurchaseOrderNew()
    {
        $pending_po = $this->latest()->first();
        if ($pending_po) {
            $pending_po_items = PurchaseOrderItem::where('purchase_order_id', $pending_po->id)->get();
            if ($pending_po->supplier_id != null || ! $pending_po_items->isEmpty()) {
                $pending_po->status = 1;
                $pending_po->save();
            } elseif ($pending_po->created_by == Auth::id()) {
                return $pending_po;
            } else {
                // no pending invoice
            }
        }
    }

    /**
     * getCode
     *
     * @param  mixed  $code
     * @return void
     */
    public function getCode(string $code)
    {
        return $this->where('code', $code)->first();
    }

    /**
     * getFormattedTotalAttribute
     *
     * @return void
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    /**
     * getPurchaseOrderDateAttribute
     *
     * @return void
     */
    public function getPurchaseOrderDateAttribute()
    {
        return date('d M Y', strtotime($this->date));
    }

    /**
     * cashier
     *
     * @return void
     */
    public function cashier()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * getCashierNameAttribute
     *
     * @return void
     */
    public function getCashierNameAttribute()
    {
        return $this->cashier && $this->cashier->status == User::STATUS['VISIBLE'] ? $this->cashier->name : '';
    }

    /**
     * currency
     *
     * @return void
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    /**
     * getCurrencyNameAttribute
     *
     * @return void
     */
    public function getCurrencyNameAttribute()
    {
        return $this->currency->code ?? null;
    }

    /**
     * supplier
     *
     * @return void
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * updateTotals
     *
     * @param  mixed  $purchase_order_id
     * @param  mixed  $total
     * @return void
     */
    public function updateTotals($purchase_order_id, $total)
    {
        $purchase_order = $this->where('id', $purchase_order_id)->withTrashed()->first();
        if ($purchase_order) {
            $purchase_order->total = $total;
            $purchase_order->save();
        }

        return $purchase_order;
    }

    /**
     * HeaderParameters
     *
     * @return void
     */
    public function headerParameters()
    {
        return $this->hasMany(PurchaseOrderItemParameter::class, 'purchase_order_id', 'id');
    }
}
