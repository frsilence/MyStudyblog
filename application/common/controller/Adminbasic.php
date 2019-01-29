<?php

namespace app\common\controller;

use think\Controller;
use think\Request;
use Log;

class Adminbasic extends Controller
{
    /**
     * 应用配置信息
     */
    protected $app_config = [];

    /**
     * 应用博客分类
     */
    protected $article_category = [];
    /**
     * 当前登录管理员用户
     */
    protected $adminuser = [];
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->adminuser = session('adminuser');
        //检查登录是否过期
        if(!empty($this->adminuser)){
            $this->checkLoginOver($this->adminuser);
        }


    }

    /**
     * 检测登录是否过期
     * @param $member
     */
    public function checkLoginOver($member)
    {
        //管理员用户每一小时必须重新登录
        $LoginDuartion = 3600;
        if(isset($member['login_time']) && !empty($LoginDuartion)){
            Log::record(time()-$member['login_time']);
            if(time()-$member['login_time'] >= $LoginDuartion){
                //用户退出并记录日志
                $this->LoginLog(2);
                //清空session数据
                $this->adminuser = [];
                session(null);
            }
            
        }
    }

    /**
     * 用户登录和退出记录
     * @param $type [0:登录账号，1:主动退出，2:登录过期退出]
     */
    public function LoginLog($type)
    {
        switch ($type) {
            case 0:
                $log = '[账户登录]正在登录应用';
                break;
            case 1:
                $log = '[主动退出]正在退出应用';
                break;
            case 2:
                $log = '[登录过期]正在退出应用';
                break;
            default:
                $log = '[未知信息]';
                break;
        }
        $login_info = getLoginInfo();
        if(!empty(session('member.id'))){
            AppMemberLoginRecord::create([
                'member_id'=>session('member.id'),
                'type'=>$type,
                'login_ip'=>$login_info['login_ip'],
                'login_area'=>$login_info['login_area'],
                'remark'=>$log]);
        }
    }
}
