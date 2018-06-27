<?php

namespace App\Traits;

use GuzzleHttp\Client;
trait PushMessage
{

    public function push($data)
    {
        //发送消息请求
        $client = new Client();
        $client->get(config('custom.PUSH_MESSAGE_URL'),[
            'query'=>[
                'type'=>'publish',
                'to'=>$data['accept_uuid'],
                'title'=>$data['title'],
                'content'=>$data['content']
            ]
        ]);
        //写入数据库
        $message = \App\Models\Message::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'send_uuid' => $data['send_uuid'],
            'accept_uuid' => $data['accept_uuid'],
            'flag' => $data['flag']
        ]);
        return $message;
    }

}


