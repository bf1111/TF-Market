<?php

namespace app\index\validate;

use think\Validate;

class UserForget extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'mobile' => 'require|mobile',   //手机号
        'password' => "require|regex:/^[a-zA-z]{1}[\w@]{7,15}$/",
        'password_confirm' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'mobile.require' => '请输入手机号',
        'mobile.mobile' => '请输入正确的手机号'
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    protected $scene = [
        'forget' => ['mobile'],
        'forgetupdate' => ['mobile','password','password_confirm']
    ];
}
