<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS = [
        'PENDING' => 0,
        'CONFIRMED' => 1,
    ];

    protected $fillable = [
        'key',
        'status',
    ];

    public function checkKey(string $key)
    {
        return $this->where('key', $key)->count();
    }

    public function getByKey(string $key)
    {
        return $this->where('key', $key)->first();
    }
}
