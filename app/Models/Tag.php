<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $guarded = ['id'];

    //与资讯多对多关联
    public function articles()
    {
        return $this->belongsToMany('App\Models\Article','article_tag','tag_id','article_id');
    }

}
