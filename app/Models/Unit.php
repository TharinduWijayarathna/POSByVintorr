<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * Unit
 * php version 8
 *
 * @category Model
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'created_by',
        'updated_by',
    ];
}
