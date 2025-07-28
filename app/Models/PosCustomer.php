<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'date_of_birth',
        'email',
        'credit',
    ];

    protected $appends = [
        'dob',
        'day',
        'month',
    ];

    public function getDobAttribute()
    {
        $date = Carbon::parse($this->date_of_birth);

        return $date->format('Y-m-d');
    }

    public function getDayAttribute()
    {
        $date = Carbon::parse($this->date_of_birth)->toArray();

        return $date['day'];
    }

    public function getMonthAttribute()
    {
        $date = Carbon::parse($this->date_of_birth)->toArray();

        return $date['month'];
    }
}
