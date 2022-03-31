<?php

namespace App\Models;

use App\Models\Setting\Role;
use App\Models\Master\Employee;
use App\Models\MazuMaster\Store;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $primaryKey = 'user_id';
    Protected $table = 'tm_user';
    Protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'role',
        'is_superuser',
        'store_id',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_id' => 'string',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    public function roles()
    {
        return $this->hasOne(Role::class, 'role_id', 'role');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'store_id', 'store_id');
    }
}
