<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');


//后台管理员
//管理员登录
Route::post('admin/login', 'admin/admin/login')->allowCrossDomain();
//中间件  判断管理员是否登录
Route::group('', function () {
    //管理员添加
    Route::post('admin/add', 'admin/admin/addAdmin');
    //管理员退出
    Route::get('admin/logout', 'admin/Admin/logout');
    //管理员修改密码
    Route::post('admin/edit', 'admin/Admin/edit');

    //用户模块
    Route::rule('admin/user/index', 'admin/User/usersList');
})->allowCrossDomain()
    ->middleware('Admin');





//----------前台--------------
//-----用户
//用户注册
Route::post('user/register', 'index/User/register')->allowCrossDomain();  //数据验证
Route::post('user/regcode', 'index/User/useUserSendNote')->allowCrossDomain();;  //得到手机验证码
//用户登录
Route::post('user/login', 'index/User/login');
//中间件  判断用户是否登录
Route::group('', function () {
    //用户退出
    Route::get('user/logout', 'index/User/logout');
    //用户编辑
    Route::post('user/edit', 'index/User/edit');
    //忘记密码(修改密码)
    Route::post('user/getforgrtreg','index/User/userForgetSendNote');  //获取验证码
    Route::post('user/forgetreg', 'index/User/forgetReg');   //验证验证码
    Route::post('user/forgetupdate', 'index/User/forgetUpdate');   //修改密码
})->allowCrossDomain();
    // ->middleware('Index');
