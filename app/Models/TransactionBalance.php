<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'code',
        'amount',
    ];
}
