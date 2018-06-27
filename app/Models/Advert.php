<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{

    protected $fillable = ['title','thumb','link','position_id','sort','description'];
    //广告所在的位置信息
    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }

}
