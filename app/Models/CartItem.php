<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit_price',
        'total'
    ];

    protected $appends = [
        'image_url',
        'product_name',
        'product_code',
        'available_qty',
        'formatted_total',
        'formatted_unit_price',
    ];

    public function getAll(int $cart_id)
    {
        return $this->where('cart_id', $cart_id)->get();
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }

    public function getImageUrlAttribute()
    {
        return $this->product ? $this->product->image_url : null;
    }

    public function getProductNameAttribute()
    {
        return $this->product ? $this->product->name : 'N/A';
    }

    public function getProductCodeAttribute()
    {
        return $this->product ? $this->product->code : 'N/A';
    }

    public function getAvailableQtyAttribute()
    {
        return $this->quantity ? $this->quantity - $this->return_quantity : 'N/A';
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }
}
