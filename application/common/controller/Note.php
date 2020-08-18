<?php

namespace app\common\controller;

use think\Controller;
use think\Request;
use Ender\YunPianSms\SMS\YunPianSms;

class Note extends Controller
{
    /**
     * 发送短信验证码
     *
     * @param string $apikey  apikey
     * @param string $mobile  手机号
     * @param string $text   短信文本内容
     * @return void
     */
    public function sendNote($apikey, $mobile, $text)
    {
        $yunpianSms = new YunPianSms($apikey);
        $response = $yunpianSms->sendMsg($mobile, $text);
        return $response;
    }
}
