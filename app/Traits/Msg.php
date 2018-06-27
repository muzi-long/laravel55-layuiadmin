<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

trait Msg{

    //检测手机号码格式
    public function verifyPhone($phone){
        return preg_match('/^1[3456789][0-9]{9}$/',$phone);
    }

    //发送短信验证码
    public function sendMsg(Request $request,$phone=null)
    {
        if (empty($phone)){
            $phone = $request->get('phone');
        }
        if (!$this->verifyPhone($phone)){
            return response()->json(['code'=>1,'msg'=>'手机号码格式错误']);
        }
        //检测图形验证码是否正确
        if (!$this->verifyCaptcha($request)){
            return response()->json(['code'=>1,'msg'=>'图形验证码不正确']);
        }
        $code = mt_rand(100000,999999);
        $content = '【转丁丁】您的验证码为：'.$code.'，有效期10分钟。';
        $msgApiUrl = "http://139.224.36.226:1082/wgws/BatchSubmit";
        $data = [
            'apName'        => 'bjdgg',         //帐号
            'apPassword'    => 'bjdgg2016',    //密码
            'srcId'         => '',              //附加号（可置空）
            'ServiceId'     => '',              //预留，可为空
            'calledNumber'  => $phone,            //手机号码
            'content'       => $content,        //内容
            'sendTime'      => ''               //发送时间，为空表示立即发送，时间格式为：yyyyMMddHHmmss
        ];
        //错误码
        $statusCode = [
            0=>"发送成功",
            3=>"密码错误",
            8=>"流量控制",
            13=>"缺少被叫",
            14=>"被叫数量太多",
            15=>"端口号验证失败",
            16=>"被叫连续下单限制",
            80=>"用户已停用",
            81=>"余额不足",
            82=>"产品未定价",
            83=>"上级产品未定价",
            84=>"缺少签名",
            85=>"网关没有报备签名",
            86=>"内容不合法",
            87=>"用户类型不匹配",
            100=>"其他异常",
            102=>"没有匹配到模板",
            103=>"未知号码运营商",
            104=>"模板没有配置运营商网关",
            106=>"非法IP",
            107=>"定时时间格式错误",
            114=>"号码与提交协议不匹配"
        ];
        $client = new Client();
        $response = $client->post($msgApiUrl,['form_params'=>$data]);
        $res = simplexml_load_string($response->getBody());
        $datas = (array)$res->submitResp;
        if ($datas['error']==0){
            session([md5($request->get('phone')) => $code]);
        }
        return response()->json(['code'=>$datas['error'],'msg'=>$statusCode[$datas['error']]]);
    }

    //验证手机短信验证码
    public function verifyMsgCode(Request $request)
    {
        if ( $request->get('msgCode') == session(md5($request->get('phone'))) ){
            $request->session()->forget(md5($request->get('phone')));
            $request->session()->forget('captcha');
            return true;
        }
        return false;
    }

    //验证图形验证码
    public function verifyCaptcha(Request $request)
    {
        return captcha_check($request->get('captcha'));
    }

}