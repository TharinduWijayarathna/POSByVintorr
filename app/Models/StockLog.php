<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE = [
        'minus' => 0,
        'plus' => 1,
    ];

    const TRANSACTION_TYPE_ID = [
        'stock_out' => 0,
        'stock_in' => 1,
    ];

    protected $fillable = [
        'product_id',
        'product_name',
        'quantity',
        'balance',
        'grn_code',
        'reason',
        'remarks',
        'type',
        'created_by',
        'created_user_name',
        'barcode', // Stock barcode
        'reference_id',
        'reference',
        'invoice_id', // Main system
        'Pos_invoice_id', // POS system
        'cost',
        'po_number',
        'transaction_type_id',
        'selling_price', // Stock selling price
        'supplier_id',
        'date',
    ];

    protected $appends = [
        'product_name',
        'created_user',
        'created_date_time',
        'transaction_type',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->withTrashed();
    }

    public function getTransactionTypeAttribute()
    {
        return $this->grnTransactionType ? $this->grnTransactionType->name : $this->remarks;
    }

    public function getProductNameAttribute()
    {
        return $this->product ? ($this->product->code.' - '.$this->product->name) : 'N/A';
    }

    public function getCreatedUserAttribute()
    {
        return $this->user ? $this->user->name : 'N/A';
    }

    public function getCreatedDateTimeAttribute()
    {
        $createdAt = $this->created_at;
        $carbon = \Carbon\Carbon::parse($createdAt);
        $date = $carbon->toDateTimeString();

        return $this->created_at ? $date : '-';
    }
}
