<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;
use Log;
use think\facade\Cookie;

/**
 * 应用用户管理控制器
 */
class Auth extends Appbasic
{
    /**
     *控制器初始化 
     */
    public function __construct()
    {
        parent::__construct();
        $action = $this->request->action();
        //已登录的会话访问注册和登录相关方法，强制跳转至首页
        if(!empty(session('member')) && $action != 'logout'){
            return $this->redirect(url('blog/index/index'));
        }

    }

    /**
     * 显示用户登录界面.
     *
     * @return \think\Response
     */
    public function login()
    {
        return $this->fetch('login',['title'=>'用户登录','article_category'=>$this->article_category]);
    }
    
    /**
     * 用户登录
     *@param  $ararry 登录信息
     * @return \think\Response
     */
    public function post_login(Request $request)
    {
        //验证传入参数
        $result = $this->validate($request->post(),'app\blog\validate\Auth.login');
        if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>$request->token()]);
        //验证用户信息
        $member_login = model('AppMember')->login($request->post('username'),$request->post('password'));
        $member_login['token'] = $request->token();
        if($member_login['code'] == 1) return json($member_login);
        //登录成功记录时间
        $member_login['member']['login_time'] = time();
        //用户信息载入session
        session('member',$member_login['member']);
        //记录登录日志
        $this->LoginLog(0);
        Cookie::set('PHPSESSID12',Cookie::get('PHPSESSID'),300);
        Cookie::set('PHPSESSID',Cookie::get('PHPSESSID'),300);
        return json($member_login);
        
    }

    

    /**
     * 显示用户注册界面
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function register()
    {
        return $this->fetch('register',['title'=>'用户注册','article_category'=>$this->article_category]);
    }

    /**
     * 用户注册
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function post_register(Request $request)
    {
        //验证传入参数
        $result = $this->validate($request->post(),'app\blog\validate\Auth.register');
        if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>$request->token()]);
        //注册用户
        $member_register = model('AppMember')->register($request->post());
        $member_register['token'] = $request->token();
        if($member_register['code'] == 1) return json($member_register);
        //注册成功，登录并记录
        if($member_register['code'] == 0) 
            {
                //登录成功记录时间
                $member_register['member']['login_time'] = time();
                //用户信息载入session
                session('member',$member_register['member']);
                //记录登录日志
                $this->LoginLog(0);
                return json($member_register);
            }

    }

    /**
     * 用户退出
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function logout()
    {
        //记录退出日志
        $this->LoginLog(1);
        //清空session
        session(null);
        return redirect(url('blog/index/index'));
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
