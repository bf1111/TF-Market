<?php

namespace app\admin\model;

use think\Model;

class Users extends Model
{
    /**
     * 所有用户信息
     *
     * @return void
     */
    public function allUsers()
    {
        $users = $this->field('id,name,mobile,status,created_time,edit_time,last_time,last_ip')->select();
        return $users;
    }
}
