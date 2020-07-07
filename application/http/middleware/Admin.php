<?php

namespace app\http\middleware;

class Admin
{
    /**
     * 判断是否登录
     *
     * @param [type] $request
     * @param \Closure $next
     * @return void
     */
    public function handle($request, \Closure $next)
    {
        if(empty(session('admin_name')) || empty(session('admin_id'))){
            ajaxReturn(10,'请先登录');
        }

        return $next($request);
    }
}
