<?php
/**
 * 文章相关基础控制器
 * 定义用户登录登出等
 */
namespace app\common\controller;

use think\Controller;
use think\Request;
use app\blog\model\BlogConfig;
use app\blog\model\AppMemberLoginRecord;

class Appbasic extends Controller
{
    /**
     * 应用配置信息
     */
    protected $app_config = [];
    /**
     * 当前登录用户
     */
    protected $member = []
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $config = new Config();
        $this->app_config = $config->get_config();
        $this->member = session('member');
        //检查登录是否过期
        if(!empty($this->member))
        {
            $this->checkLoginOver($this->member);
        }


    }

    /**
     * 检测登录是否过期
     * @param $member
     */
    public function checkLoginOver($member)
    {
        if(isset($this->app_config['LoginDuartion']) && !empty($this->app_config['LoginDuartion'])){
            $LoginDuartion = $this->app_config['LoginDuartion'];
        }
        else{
            $LoginDuartion = '';
        }
        if(isset($member['login_time']) && !empty($LoginDuartion)){
            //用户退出并记录日志
            $this->LoginLog(2)
            //清空session数据
            $this->member = [];
            session(null);
        }
    }

    /**
     * 用户登录和退出记录
     * @param $type [0:登录账号，1:主动退出，2:登录过期退出]
     */
    public LoginLog($type)
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
                $log = '[未知信息]'
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
