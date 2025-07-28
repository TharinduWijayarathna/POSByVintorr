<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
 * Configuration
 * php version 8
 *
 * @category Model
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class Configuration extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS = [
        'DISABLED' => 0,
        'ENABLED' => 1,
    ];

    protected $fillable = [
        'email',
        'mobile',
        'mobile2',
        'address',
        'time_zone',
        'currency',
        'summary_date',
        'sale_target',
        'vat_no',
        'tin_no',
        'description',
        'outstanding',
        'name',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'currency_code'
    ];
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }

    public function getCurrencyCodeAttribute()
    {
        $currency_id = $this->currency;
        $currency = Currency::where('id',$currency_id)->first();
        return $currency->code;
    }

}
