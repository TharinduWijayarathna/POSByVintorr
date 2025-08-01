<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'symbol'];

    public function configuration()
    {
        return $this->hasMany(configuration::class, 'currency', 'id');
    }
}
