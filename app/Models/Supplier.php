<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE = [
        0 => 'Supplier',
        1 => 'Employee',
    ];

    protected $fillable = [
        'name',
        'company',
        'address',
        'website',
        'contact',
        'contact2',
        'contact3',
        'email',
        'email2',
        'email3',
        'status',
        'account_no',
        'bank_name',
        'branch_name',
        'type',
        'created_by',
        'updated_by',
    ];
}
