<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigGroup extends Model
{
    protected $table = 'config_group';
    protected $fillable = ['name','sort'];

    //配置项
    public function configurations()
    {
        return $this->hasMany('App\Models\Configuration','group_id','id');
    }
}
