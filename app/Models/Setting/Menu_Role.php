<?php

namespace App\Models\Setting;

use App\Models\Setting\Menu;
use App\Models\Setting\Role;
use Illuminate\Database\Eloquent\Model;


class Menu_Role extends Model
{
    protected $primaryKey = 'menu_role_id';
    public $incrementing = false;
    protected $table = 'ts_menu_role';
    protected $fillable = ['menu_id', 'role_id', 'access', 'create', 'read', 'update', 'delete'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function menu()
    {
    return $this->hasOne(Menu::class, 'menu_id', 'menu_id')->orderBy('order', 'ASC');
    }
}
