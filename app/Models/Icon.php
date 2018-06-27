<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $table = 'icons';
    protected $fillable = ['unicode','class','name','sort'];

    //对应菜单
    public function menus()
    {
        return $this->hasMany('App\Models\Menu','icon_id','id');
    }
}
