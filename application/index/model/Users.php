<?php

namespace app\index\model;

use think\Model;

class Users extends Model
{
    /**
     * 添加用户
     *
     * @param [type] $data 添加用户数据
     * @return void
     */
    public function addUser($data)
    {
        $res = $this->strict(false)->insert($data);
        return $res;
    }

    /**
     * 判断用户名是否已经注册
     *
     * @param [type] $data  查询条件
     * @return void
     */
    public function userLogin($mobile)
    {
        $res = $this->where('mobile', $mobile)->find();
        return $res;
    }

    /**
     * 用户登录更新数据
     *
     * @param [type] $id  用户id
     * @param [type] $data 更新的数据
     * @return void
     */
    public function updateUserLogin($id, $data = [])
    {
        $res = $this->where('id', $id)->update($data);
        return $res;
    }

    /**
     * 跟新用户密码
     *
     * @return void
     */
    public function forgetUpdatePwd($mobile,$data)
    {
        $res = $this->where('mobile',$mobile)->update($data);
        return $res;
    }
}
