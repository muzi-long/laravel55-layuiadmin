<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $fillable = ['adcode','name','center','level','parent_id','sort','enable'];
}
