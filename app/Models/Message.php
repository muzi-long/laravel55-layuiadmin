<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['title','content','read','send_uuid','accept_uuid','flag'];

    public $read_status=[
        '1'=>'未读',
        '2'=>'已读'
    ];

}
