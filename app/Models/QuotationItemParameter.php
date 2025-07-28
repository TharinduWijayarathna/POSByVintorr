<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItemParameter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'parameter_id',
        'quotation_id',
        'name',
        'description',
        'order',
    ];
}
