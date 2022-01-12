<?php

namespace App\Models\Setting;

use App\Models\Setting\Menu_Role;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'role_id';
    protected $table = 'tm_role';
    protected $fillable = ['role_name','is_active'];

    public function menu_role()
    {
        return $this->hasMany(Menu_Role::class, 'role_id', 'role_id');
    }

}
