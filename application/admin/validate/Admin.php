<?php

namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require',  //管理员用户名
        'username' => 'require|unique:admin,name',
        'password' => 'require',  //管理员密码
        'oldpassword' => 'require',   //旧密码
        'newpassword' => 'require|min:6|max:16'  //新密码
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '请输入用户名',
        'username.require' => '请输入用户名',
        'username.unique' => '该用户名已存在',
        'password.require' => '请输入密码',
        'oldpassword.require' => '请输入原密码',
        'newpassword.require' => '请输入新密码',
        'newpassword.min' => '新密码长度最少6个字符',
        'newpassword.max' => '新密码长度最多16个字符'
    ];

    /**
     * 验证场景
     *
     * @var array
     */
    protected $scene = [
        'login' => ['name','password'],
        'add' => ['username','password'],
        'edit' => ['oldpassword','newpassword']
    ];
}
