<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use LaravelPermissionToVueJS;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'user_role',
        'user_role_id',
        'status',
        'mobile_no',
        'land_no',
        'address',
        'created_by',
        'updated_by',
    ];

    const USER_ROLE_ID = [
        'ADMIN' => '1',
        'CASHIER' => '2',
        'AUDIT' => '3',
        'INSPECTOR' => '4',
    ];

    const STATUS = [
        'VISIBLE' => '0',
        'HIDDEN' => '1',
    ];

    protected $appends = [
        'role_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'user_role_id');
    }

    public function getRoleNameAttribute()
    {
        return Role::find($this->user_role_id)->name ?? 'N/A';
    }
}
