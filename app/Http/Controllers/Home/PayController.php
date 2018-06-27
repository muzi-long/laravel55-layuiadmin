<?php

namespace App\Http\Controllers\Home;


use App\Models\Member;
use App\Http\Controllers\Controller;
use Yansongda\LaravelPay\Facades\Pay;
use Exception;
use Log;
use QrCode;

class PayController extends Controller
{
    //微信支付
    public function wechatPay()
    {
        $order = [
            'out_trade_no' => time(),
            'body' => 'subject-测试',
            'total_fee' => 1,
        ];
        $result = Pay::wechat()->scan($order);
        $qr = $result->code_url;
        return QrCode::size(200)->generate($qr);
    }

    //微信支付回调
    public function wechatNotify()
    {
        $pay = Pay::wechat();
        try {
            // 验签！
            $data = $pay->verify();
            Log::debug('Wechat notify', $data->all());
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
