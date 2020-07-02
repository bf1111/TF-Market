<?php

namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name' => 'require|min:6|max:16|unique:users,name',
        'mobile' => 'require|mobile|unique:users,mobile',   //手机号
        'password' => "require|regex:/^[a-zA-z]{1}[\w@]{7,15}$/",
        'password_confirm' => 'require',
        'code' => 'require'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '用户名不能为空',
        'name.min' => '用户名长度最少6个字符',
        'name.max' => '用户名长度最多16个字符',
        'name.unique' => '该用户名已存在',
        'mobile.require' => '请输入手机号',
        'mobile.mobile' => '请输入正确的手机号',
        'mobile.unique' => '该手机号已被注册',
        'password.require' => '密码不能为空',
        'password.regex' => '密码为首位字母的且长度为8-16为字符',
        'password_confirm' => '请重复密码',
        'password.confirm' => '两次密码不一致',
        'code' => '请输入验证码'
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    protected $scene = [
        'register' => ['name','mobile','password','password_confirm','code'],
    ];
}
