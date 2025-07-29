<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * Tax
 * php version 8
 *
 * @category Model
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 * @link     https://Vintorr.com
 * */
class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'abbreviation',
        'created_by',
        'updated_by',
    ];
}
