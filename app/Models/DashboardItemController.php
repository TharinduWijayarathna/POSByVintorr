<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardItemController extends Model
{
    use HasFactory;

    const STATUS = [
        'DISABLED' => 0,
        'ENABLED' => 1,
    ];
    
    protected $fillable = [
        'status',
        'dashboard_item_id',
        'name'
    ];

    public function dashboardItem()
    {
        return $this->hasMany(DashboardItem::class, 'dashboard_item_id', 'id');
    }
}
