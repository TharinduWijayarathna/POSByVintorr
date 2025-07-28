<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class UserPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'no_of_month',
        'date',
        'expired_date',
        'note',
    ];

    protected $appends = [
        'date_formatted',
    ];

    public function getDateFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('d M Y');
    }
}
