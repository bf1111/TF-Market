<?php

namespace app\http\middleware;

class Index
{
    public function handle($request, \Closure $next)
    {
        if(empty(session('user_name')) || empty(session('user_id')))
        {
            ajaxReturn(10,'请先登录');
        }
        return $next($request);
    }
}
