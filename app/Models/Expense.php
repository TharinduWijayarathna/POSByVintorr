<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE = [
        0 => 'Expense',
        1 => 'Salary Payment',
    ];

    protected $fillable = [
        'code',
        'type',
        'expense_category_id',
        'supplier_id',
        'date',
        'description',
        'remark',
        'currency_id',
        'amount',
        'image_id',
    ];

    protected $appends = [
        'image_url',
        'category_name',
        'supplier_name',
        'currency_name',
        'formatted_amount',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image->name ?? null;
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function getCategoryNameAttribute()
    {
        return $this->expenseCategory ? $this->expenseCategory->name : 'N/A';
    }

    public function expenseCategory()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'expense_category_id');
    }

    public function getSupplierNameAttribute()
    {
        return $this->supplier ? $this->supplier->name : 'N/A';
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id')->withTrashed();
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency ? $this->currency->code : 'N/A';
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }
}
