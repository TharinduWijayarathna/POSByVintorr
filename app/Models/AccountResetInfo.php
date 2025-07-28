<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountResetInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'email',
        'current_date',
        'expiration_date',
        'is_send',
        'count',
        'status',
    ];
}
