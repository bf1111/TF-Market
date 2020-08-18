<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * ajax返回信息
 *
 * @param [type] $status 状态码
 * @param [type] $msg  提示信息
 * @param array $data  返回数据
 * @return void
 */
function ajaxReturn($status, $msg, $data = [])
{
    header('Content-Type: application/json; charset=utf8');  //json传递
    echo json_encode([
        'status' => $status,
        'msg' => $msg,
        'data' => $data
    ]);
    exit;
}

/**
 * 生成随机的字符串
 *
 * @param integer $length  长度
 * @return void
 */
function cShuffleStr($length = 3)
{
    return bin2hex(random_bytes($length));
}


/**
 * 发送验证码
 *
 * @param [type] $apikey 配置
 * @param [type] $mobile 手机号
 * @param [type] $text 内容
 * @return void
 */
function sendNotes($apikey, $mobile, $text)
{
    // 开启句柄
    $ch = curl_init();

    /* 设置验证方式 */
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept:text/plain;charset=utf-8',
        'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'
    ));

    /* 设置返回结果为流 */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* 设置超时时间*/
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    /* 设置通信方式 */
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // 发送短信
    $data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
    $json_data = send($ch, $data);
    $array = json_decode($json_data, true);
    // echo '<pre>';
    // print_r($array);
    // 关闭句柄
    curl_close($ch);
    return $array;
}

/**
 * 发送
 *
 * @param [type] $ch   由 curl_init() 返回的 cURL 句柄。
 * @param [type] $data 发送的数据
 * @return void
 */
function send($ch, $data)
{
    curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
    $error = curl_error($ch);
    checkErr($result, $error);
    return $result;
}

/**
 * 检查发送是否错误
 *
 * @param [type] $result 结果
 * @param [type] $error 报错信息
 * @return void
 */
function checkErr($result, $error)
{
    if ($result === false) {
        echo 'Curl error: ' . $error;
    }
}

/**
 * 生成六位随机数字
 *
 * @return void
 */
function createCode()
{
    $getCode = '';
    for ($i = 0; $i <= 5; $i++) {
        $getCode .= mt_rand(0, 9);
    }
    return $getCode;
}
