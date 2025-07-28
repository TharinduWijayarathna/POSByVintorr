<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE = [
        'POS' => 1,
        'INVOICE' => 2,
        'EXPENSE' => 3,
        'RETURN' => 4,
        'MANUAL' => 5,
    ];

    const SIGN = [
        'PLUS' => 1,
        'MINUS' => 0,
    ];

    protected $fillable = [
        'code',
        'type',
        'date',
        'reference_code',
        'payment_code',
        'customer_id',
        'supplier_id',
        'client',
        'currency_id',
        'amount',
        'sign',
        'description',
        'remark',
    ];

    protected $appends = [
        'currency_name',
        'formatted_amount',
    ];

    public function getCode(string $code)
    {
        return $this->where('code', $code)->first();
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency->code ?? null;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format(max($this->amount, 0), 2);
    }
}
