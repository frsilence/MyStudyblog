<?php

namespace app\http\middleware;

class AdminAuth
{
    public function handle($request, \Closure $next)
    {
    	if(session('?member.id'))  return $next($request);	
    	$path = $request->path();
    	if(preg_match("/^api/",$path)){
    		//api类
    		return json(['code'=>1,'msg'=>'未登录，先登录再操作！']);
    	}else{
    		//页面请求类
    		return redirect(url('admin/auth/login'));
    	}	
    }
}
