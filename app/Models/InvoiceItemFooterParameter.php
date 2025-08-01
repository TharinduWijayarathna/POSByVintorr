<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItemFooterParameter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'parameter_id',
        'invoice_id',
        'name',
        'description',
        'order',
    ];
}
