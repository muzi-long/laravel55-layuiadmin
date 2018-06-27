<?php
namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{


    //菜单图标
    public function icon()
    {
        return $this->belongsTo('App\Models\Icon','icon_id','id');
    }

    //子权限
    public function childs()
    {
        return $this->hasMany('App\Models\Permission','parent_id','id');
    }

}