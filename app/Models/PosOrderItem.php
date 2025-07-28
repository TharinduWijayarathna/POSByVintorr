<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosOrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE = [
        'PRODUCT' => 0,
        'VOUCHER' => 1,
    ];

    const RETURN_STATUS = [
        'order' => 0,
        'return' => 1,
    ];

    protected $fillable = [
        'order_id',
        'product_id', // Finish Good Id - Material Id
        'unit_price',
        'quantity',
        'sub_total',
        'total',
        'total_tax',
        'discount',
        'discount_type',
        'type',
        'voucher_id',
        'return_status',
        'return_quantity',
        'discount_remark',
        'product_name',
        'description',
    ];

    protected $appends = [
        'image_url',
        'product_code',
        'available_qty',
        'formatted_sub_total',
        'formatted_total',
        'formatted_total_tax',
        'formatted_unit_price',
    ];

    public function getImageUrlAttribute()
    {
        return $this->product ? $this->product->image_url : null;
    }

    public function fgData()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }

    public function getProductCodeAttribute()
    {
        return $this->product ? $this->product->code : 'N/A';
    }


    /**
     * getAll
     * Get all order items according to the given id
     *
     * @param  int  $order_id
     * @return void
     */
    public function getAll(int $order_id)
    {
        return $this->where('order_id', $order_id)->get();
    }

    /**
     * total
     * Get all order items total sum according to the given id
     *
     * @param  int $order_id
     * @return void
     */
    public function subTotal(int $order_id)
    {
        return $this->where('order_id', $order_id)->get()->sum('sub_total');
    }

    /**
     * totalTax
     *
     * @param  mixed $order_id
     * @return void
     */
    public function totalTax(int $order_id)
    {
        return $this->where('order_id', $order_id)->get()->sum('total_tax');

    }

    /**
     * getReturnItem
     *
     * @param  mixed $order_id
     * @return void
     */
    public function getReturnItem(int $order_id)
    {
        return $this->where('order_id', $order_id)->where('return_status', 1)->get();
    }

    /**
     * getWithoutReturnItems
     *
     * @param  mixed $order_id
     * @return void
     */
    public function getWithoutReturnItems(int $order_id)
    {
        return $this->where('order_id', $order_id)->where('return_status', 0)->get();
    }

    /**
     * getAvailableQtyAttribute
     *
     * @return void
     */
    public function getAvailableQtyAttribute()
    {
        return $this->quantity ? $this->quantity - $this->return_quantity : 'N/A';
    }

    /**
     * getFormattedSubTotalAttribute
     *
     * @return void
     */
    public function getFormattedSubTotalAttribute()
    {
        return number_format($this->sub_total, 2);
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
     * getFormattedUnitPriceAttribute
     *
     * @return void
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }

    /**
     * getFormattedTotalTaxAttribute
     *
     * @return void
     */
    public function getFormattedTotalTaxAttribute()
    {
        return number_format($this->total_tax, 2);
    }
}
