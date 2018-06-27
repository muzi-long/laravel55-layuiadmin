<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['name','sort'];
    //该位置下所有的广告
    public function adverts()
    {
        return $this->hasMany('App\Models\Advert');
    }

}
