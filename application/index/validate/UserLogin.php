<?php

namespace app\index\validate;

use think\Validate;

class UserLogin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'mobile' => 'require|mobile',
        'password' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'mobile.require' => '请输入手机号',
        'mobile.mobile' => '请输入正确的手机号',
        'password.require' => '密码不能为空'
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    protected $scene = [
        'login' => ['mobile', 'password']
    ];
}
