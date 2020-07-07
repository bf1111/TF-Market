<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    /**
     * 管理员登录逻辑
     *
     * @param [type] $name  用户名
     * @param [type] $password  密码
     * @return boolean
     */
    public function isAdmin($name)
    {
        $condition = ['name' => $name];
        $res = $this->where($condition)->find();
        return $res;
    }

    /**
     * 添加管理员逻辑
     *
     * @param [type] $data  添加的数据
     * @return void
     */
    public function addAdmin($data)
    {
        $res = $this->strict(false)->insert($data);
        return $res;
    }

    /**
     * 修改密码
     *
     * @param array $data  更新的数据
     * @return void
     */
    public function updatePws($data=[])
    {
        // 修改密码
        $condition = ['id' => session('admin_id'), 'name' => session('admin_name')];
        // $data = ['password' => $newpassword, 'code' => $code];
        $res = $this->where($condition)->update($data);
        return $res;
    }


    /**
     * 管理员登录更新数据
     *
     * @param [type] $id
     * @param array $data
     * @return void
     */
    public function updateAdminLogin($id, $data = [])
    {
        $res = $this->where('id', $id)->update($data);
        return $res;
    }
}
