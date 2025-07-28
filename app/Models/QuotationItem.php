<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'product_name',
        'description',
        'quantity',
        'unit_price',
        'sub_total',
        'total_tax',
        'total',
    ];

    protected $appends = [
        'product_code',
        'available_qty',
        'formatted_sub_total',
        'formatted_total',
        'formatted_total_tax',
        'formatted_unit_price',
    ];

    /**
     * getAll
     * Get all order items according to the given id
     *
     * @return void
     */
    public function getAll(int $quotation_id)
    {
        return $this->where('quotation_id', $quotation_id)->get();
    }

    public function subTotal(int $quotation_id)
    {
        return $this->where('quotation_id', $quotation_id)->get()->sum('sub_total');
    }

    /**
     * totalTax
     *
     * @param  mixed  $quotation_id
     * @return void
     */
    public function totalTax(int $quotation_id)
    {
        return $this->where('quotation_id', $quotation_id)->get()->sum('total_tax');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }

    public function getProductCodeAttribute()
    {
        return $this->product ? $this->product->code : 'N/A';
    }

    public function getAvailableQtyAttribute()
    {
        return $this->quantity ? $this->quantity - $this->return_quantity : 'N/A';
    }

    public function getFormattedSubTotalAttribute()
    {
        return number_format($this->sub_total, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getFormattedTotalTaxAttribute()
    {
        return number_format($this->total_tax, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }
}
