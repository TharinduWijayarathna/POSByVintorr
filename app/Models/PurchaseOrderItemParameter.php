<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItemParameter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'parameter_id',
        'purchase_order_id',
        'name',
        'description',
        'order',
    ];
}
