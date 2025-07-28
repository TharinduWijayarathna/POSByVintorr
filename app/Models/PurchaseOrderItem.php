<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'product_name',
        'description',
        'quantity',
        'cost',
        'total',
    ];

    protected $appends = [
        'product_code',
        'formatted_total',
        'formatted_cost',
    ];

    /**
     * getAll
     *
     * @param  mixed  $purchase_order_id
     * @return void
     */
    public function getAll(int $purchase_order_id)
    {
        return $this->where('purchase_order_id', $purchase_order_id)->get();
    }

    /**
     * product
     *
     * @return void
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }

    /**
     * total
     *
     * @param  mixed  $purchase_order_id
     * @return void
     */
    public function total(int $purchase_order_id)
    {
        return $this->where('purchase_order_id', $purchase_order_id)->get()->sum('total');
    }

    /**
     * getProductCodeAttribute
     *
     * @return void
     */
    public function getProductCodeAttribute()
    {
        return $this->product ? $this->product->code : 'N/A';
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
     * getFormattedCostAttribute
     *
     * @return void
     */
    public function getFormattedCostAttribute()
    {
        return number_format($this->cost, 2);
    }
}
