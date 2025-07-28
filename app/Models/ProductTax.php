<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'tax_id',
        'tax_name',
        'tax_abbreviation',
        'tax_rate',
    ];
}
