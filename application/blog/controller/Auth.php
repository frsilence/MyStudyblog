<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;

/**
 * 应用用户管理控制器
 */
class Auth extends Controller
{
    //请求方式
    protected $method = null;

    /**
     *控制器初始化 
     */
    public function __construct()
    {
        parent::__construct();
        $this->method = $this->request->method();
    }

    /**
     * 显示用户登录界面.
     *
     * @return \think\Response
     */
    public function login()
    {
        return $this->fetch('login',['title'=>'用户登录']);
    }
    
    /**
     * 用户登录
     *@param  $ararry 登录信息
     * @return \think\Response
     */
    public function post_login(Request $request)
    {
        //验证传入参数
        $result = $this->validate($request->post(),'app\blog\validate\auth.login');
        if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>$request->token()]);
        //验证用户信息
        $member_login = model('AppMember')->login($request->post('username'),$request->post('password'));
        $member_login['token'] = $request->token();
        if($member_login['code'] == 1) return $member_login;
        //登录成功记录时间
        $member_login['member']['login_at'] = time();
        //用户信息载入session
        session('member',$member_login['member']);
        //记录登录日志
        $this->LoginLog(0);
        
    }

    

    /**
     * 用户注册界面
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function register()
    {
        return $this->fetch('register',['title'=>'用户注册']);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
