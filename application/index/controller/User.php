<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Validate;

class User extends Controller
{

    protected $usersModel;

    /**
     * 控制器初始化
     *
     * @return void
     */
    public function initialize()
    {
        $this->usersModel = model('Users');
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        //判断是否POST提交
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }

        //验证器 验证
        $data = input('post.');
        $validate = validate('User');
        if (!$validate->scene('register')->check($data)) {
            ajaxReturn(2, $validate->getError());
        }
        //判断两次密码是否一致
        if ($data['password'] != $data['password_confirm']) {
            ajaxReturn(2, '两次密码不一致');
        }
        //判断手机验证码是否正确
        // session('mobile_code',1111);
        if ($data['code'] != session('mobile_code')) {
            ajaxReturn(2, '验证码不正确');
        }
        //逻辑
        //处理数据
        $data['code'] = createSalt();  //生成密码盐值
        $data['password'] = md5($data['password'] . $data['code']);
        $data['mobile'] = intval($data['mobile']);
        $data['created_time'] = time();
        $data['edit_time'] = time();
        //数据入库
        $res = $this->usersModel->addUser($data);
        if ($res) {
            //清空mobile_code
            session('mobile_code', null);
            ajaxReturn(0, '注册成功');
        } else {
            ajaxReturn(2, '注册失败');
        }
    }

    /**
     * 用户登录
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        //判断是否POST提交
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }

        //验证器 处理数据
        $data = input('post.');
        $validate = validate('UserLogin');
        if (!$validate->scene('login')->check($data)) {
            ajaxReturn(2, $validate->getError());
        }

        //逻辑
        $res = $this->usersModel->userLogin($data['mobile']);
        if (!$res) {
            ajaxReturn(2, '该手机号不存在');
        }
        //判断密码是否正确
        if (md5($data['password'] . $res['code']) == $res['password']) {
            //存储session
            session('user_name', $res['name']);
            session('user_id', $res['id']);
            //更新数据
            $upData['last_time'] = time();
            $upData['last_ip'] = $_SERVER['REMOTE_ADDR'];
            if ($this->usersModel->updateUserLogin($res['id'], $upData)) {
                ajaxReturn(0, '登录成功');
            } else {
                ajaxReturn(2, '服务端错误');
            }
        } else {
            ajaxReturn(2, '密码不正确');
        }
    }

    /**
     * 用户退出
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        //清空session
        session('user_name', null);
        session('user_id', null);
        if (empty(session('user_name')) && empty(session('user_id'))) {
            ajaxReturn(0, '退出成功');
        } else {
            ajaxReturn(2, '退出失败');
        }
    }

    /**
     * 用户编辑
     *
     * @return void
     */
    public function edit(Request $request)
    {
        //判断是否POST提交
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }

        //独立验证 验证
        $data = input('post.');
        session('user_id', 3);
        $user_id = session('user_id');
        $rule = [
            'name' => "require|min:6|max:16|unique:users,name,$user_id",
            'password' => 'require|regex:/^[a-zA-z]{1}[\w@]{7,15}$/',
            // 'password' => '/^[a-zA-z]{1}[\w@]{7,15}$/',
            'password_confirm' => 'require'
        ];
        $message = [
            'name.require' => '用户名不能为空',
            'name.min' => '用户名长度不能小于6个字符',
            'name.max' => '用户名长度不能大于16个字符',
            'name.unique' => '该用户名已被注册',
            'password.require' => '新密码不能为空',
            'password_confirm.require' => '请再次输入新密码',
            'password.regex' => '密码为首位字母的且长度为8-16为字符'
        ];
        $validate   = Validate::make($rule, $message);
        if (!$validate->check($data)) {
            ajaxReturn(2, $validate->getError());
        }
        //判断两次密码是否一致
        if ($data['password'] != $data['password_confirm']) {
            ajaxReturn(2, '您输入的两次密码不一致');
        }

        //逻辑
        //更新数据
        $updateData['code'] = createCode();  //生成密码盐值
        $updateData['password'] = md5($data['password'] . $updateData['code']);
        $updateData['edit_time'] = time();
        $res = $this->usersModel->updateUserLogin(session('user_id'), $updateData);
        if ($res) {
            session('user_name', null);
            session('user_id', null);
            ajaxReturn(0, '编辑成功');
        } else {
            ajaxReturn(2, '编辑失败');
        }
    }

    /**
     * 忘记密码(找回密码 验证手机号)
     *
     * @param Request $request
     * @return void
     */
    public function forgetReg(Request $request)
    {
        //判断是否POST提交
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }

        //验证器 验证
        $data = input('post.');
        $validate = validate('UserForget');
        if (!$validate->scene('forget')->check($data)) {
            ajaxReturn(2, $validate->getError());
        }

        //判断手机号是否已存在
        $res = $this->usersModel->userLogin($data['mobile']);
        if (empty($res)) {
            ajaxReturn(2, '该手机号未注册');
        }

        //判断code是否为空
        if (empty($data['code'])) {
            ajaxReturn(2, '请输入六位数字的短信验证码');
        }
        // 验证code
        $regCode = '/^\d{6}$/';
        if (!preg_match($regCode, $data['code'])) {
            ajaxReturn(2, '请输入六位数字的短信验证码');
        }

        //判断验证码是否一致
        // session('forget_code', 111111);
        if (session('forget_code') == $data['code']) {
            session('forget_code', null);
            ajaxReturn(0, '验证成功');
        } else {
            ajaxReturn(2, '验证码错误');
        }
    }

    /**
     * 忘记密码（找回密码 密码更新）
     *
     * @param Request $request
     * @return void
     */
    public function forgetUpdate(Request $request)
    {
        //判断是否POST提交
        if(!$request->isPost()){
            ajaxReturn(2,'请求不合法');
        }

        //验证器 验证数据
        $data = input('post.');
        $validate = validate('UserForget');
        if(!$validate->scene('forgetupdate')->check($data)){
            ajaxReturn(2,$validate->getError());
        }

        //验证成功后更新数据
        $updateData['password'] = $data['password'];
        $updateData['edit_time'] = time();
        $res = $this->usersModel->forgetUpdatePwd($data['mobile'],$updateData);
        if($res){
            ajaxReturn(0,'密码修改成功');
        }else{
            ajaxReturn(2,'密码修改失败');
        }
    }

    /**
     * 用户找回密码发送验证码
     *
     * @param Request $request
     * @return void
     */
    public function userForgetSendNote(Request $request)
    {
        //判断请求是否合法
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }
        $code = createCode();
        $mobile = input('post.mobile');
        $this->userSendNote($code, $mobile, 'forget_code');
    }


    /**
     * 调用用户发送验证码（用户注册）
     *
     * @param Request $request
     * @return void
     */
    public function useUserSendNote(Request $request)
    {
        //判断请求是否合法
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }
        $code = createCode();
        $mobile = input('post.mobile');
        $this->userSendNote($code, $mobile, 'mobile_code');
    }

    /**
     * 用户发送手机验证码
     *
     * @param [type] $code
     * @param [type] $mobile
     * @return void
     */
    public function userSendNote($code, $mobile, $sessionName)
    {
        //本次能否发送
        $flag = false;
        if (empty(session('send_last_time'))) {
            $flag = true;
        } else {
            $nowTime = time();
            if ($nowTime - session('send_last_time') > 60) {
                $flag = true;
            }
        }

        //能否发送
        if (!$flag) {
            ajaxReturn(2, '请不要频繁点击');
        } else {
            $text = config('text_start') . $code . config('text_end');
            $note = sendNotes(config('apikey'), $mobile, $text);
            session('send_last_time', time());
            if ($note['code'] == 0) {
                session($sessionName, $code);  //存储session
                ajaxReturn('0', '发送成功');
            } else {
                ajaxReturn('2', $note['mag']);
            }
        }
    }
}
