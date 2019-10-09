<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
    protected $table = 'operation_logs';
    protected $fillable = ['user_id','username','realname','ip','method','uri','query'];
}
