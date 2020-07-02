<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

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
     * 用户列表
     *
     * @return void
     */
    public function usersList()
    {
        //逻辑
        //所有用户信息
        $users = $this->usersModel->allUsers();
        ajaxReturn(0,'success',$users);
    }
}
