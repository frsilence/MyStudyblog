<?php

namespace app\http\middleware;
use Log;
class BlogAuth
{
    public function handle($request, \Closure $next)
    {
    	if(session('?member.id'))  return $next($request);	
    	$path = $request->path();
    	//api类
    	if(preg_match("/^api/",$path)){
    		return json(['code'=>1,'msg'=>'未登录，非法访问']);
    	}else{
    		return redirect(url('blog/auth/login'));
    	}	
    }
}
