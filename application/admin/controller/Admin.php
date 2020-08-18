<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Admin extends Controller
{
    protected $adminModel;

    /**
     * 控制器初始化
     *
     * @return void
     */
    public function initialize()
    {
        $this->adminModel = model('admin');   //Admin模型
    }

    /**
     * 管理员登录逻辑
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

        //验证器 验证
        $data = input('post.');   //接收数据
        $validate = validate('Admin');
        if (!$validate->scene('login')->check($data)) {
            ajaxReturn(2, $validate->getError());
        }

        //逻辑
        $res = $this->adminModel->isAdmin($data['name']);
        if (!$res) {
            ajaxReturn(2, '用户名不存在');
        }
        $data['password'] = md5($data['password'] . $res['code']);
        if ($data['password'] == $res['password']) {
            //存储session
            session('admin_name', $res['name']);  //用户名
            session('admin_id', $res['id']);   //管理员id
            //更新数据
            $upData['last_time'] = time();
            $upData['last_ip'] = $_SERVER['REMOTE_ADDR'];
            if ($this->adminModel->updateAdminLogin($res['id'], $upData)) {
                ajaxReturn(0, '登录成功');
            } else {
                ajaxReturn(2, '服务端错误');
            }
        } else {
            ajaxReturn(2, '密码不正确');
        }
    }

    /**
     * 管理员退出逻辑
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        //清空session
        session('admin_name', null);
        session('admin_id', null);
        session(null);
        if (empty(session('admin_name')) && empty(session('admin_id'))) {
            ajaxReturn(0, '退出成功');
        } else {
            ajaxReturn(2, '退出失败');
        }
    }

    /**
     * 管理员编辑逻辑(修改密码)
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        //判断是否POST提交
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }

        //验证器  验证数据
        $data = input('post.');
        $validate = validate('Admin');
        if (!$validate->scene('edit')->check($data)) {
            ajaxReturn(2, $validate->getError());
        }
        //判断原密码和新密码是否一致
        if ($data['oldpassword'] == $data['newpassword']) {
            ajaxReturn(2, '原密码和旧密码一致');
        }

        //逻辑
        //判断旧密码是否正确
        $res = $this->adminModel->isAdmin(session('admin_name'));
        if (md5($data['oldpassword'] . $res['code']) != $res['password']) {
            ajaxReturn(2, '旧密码不正确');
        }
        //判断新密码是否更新成功
        $code = cShuffleStr();  //生成密码盐值
        $newpassword = md5($data['newpassword'] . $code);  //md5加密密码
        $data = [
            'password' => $newpassword,
            'code' => $code,
            'edit_time' => time()
        ];

        //更新数据
        if ($this->adminModel->updatePws($data)) {
            //清空session
            session('admin_name', null);
            session('admin_id', null);
            ajaxReturn(0, '密码修改成功,请重新登录');
        } else {
            ajaxReturn(2, '密码修改失败');
        }
    }

    /**
     * 添加管理员
     *
     * @return void
     */
    public function addAdmin(Request $request)
    {
        //判断是否POST提交
        if (!$request->isPost()) {
            ajaxReturn(2, '请求不合法');
        }

        //验证器 验证
        $data = input('post.');
        $validate = validate('Admin');
        if (!$validate->scene('add')->check($data)) {
            ajaxReturn(2, $validate->getError());
        }

        //逻辑
        $data['code'] = cShuffleStr();  //密码盐值
        $data['password'] = md5($data['password'] . $data['code']);  //密码加密
        $data['name'] = $data['username'];
        $data['created_time'] = $data['edit_time'] = time();  //生成时间戳
        $res = $this->adminModel->addAdmin($data);
        if ($res) {
            ajaxReturn(0, '管理员新增成功');
        } else {
            ajaxReturn(2, '管理员新增失败');
        }
    }
}
