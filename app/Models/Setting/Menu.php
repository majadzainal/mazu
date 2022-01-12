<?php

namespace App\Models\Setting;

use App\Models\Setting\Menu_Role;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $primaryKey = 'menu_id';
    public $incrementing = false;
    protected $table = 'tm_menu';
    protected $fillable = ['menu_id','menu_name'];

    public function menu_role()
    {
        return $this->belongsTo(Menu_Role::class, 'role_id', 'role_id');
    }

    public function menu_parents()
    {
        return $this->hasOne(Menu::class, 'menu_id', 'parent_menu');
    }

}
