<?php
/**
 * Created by 顶呱呱.
 * User: 李帅
 * Date: 2019/2/25
 * Time: 15:49
 */

namespace App\Http\Requests;

use Illuminate\Validation\Validator;

class Common extends Validator{
    //验证中文长度
    public function ValidateChinese($attribute, $value, $parameters){
        if(mb_strlen(trim(strip_tags($value)))<=$parameters[0]){
            return true;
        }else{
            return false;
        }
    }
}