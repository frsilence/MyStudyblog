<?php

namespace app\blog\model;

use think\Model;
use Log;

/**
 * 登录记录
 */
class AppMemberLoginRecord extends Model
{
    protected $table = 'app_member_login_record';

    /**
     * 获取用户登录记录
     * @param  int $member 指定用户id
     * @param  int $page 每页获取数量
     * @reurn \think\Paginator 
     */
    public function getMemberLoginRecord($member_id,$page=15)
    {
    	$login_record = $this->where(['member_id'=>$member_id])->order('create_at','desc')->paginate($page);
    	return $login_record;
    }

    /**
     * 获取上次登录时间 (用于登录过期检测)
     * @param int $member
     * @return  string
     */
    public function getLastLoginTime($member_id)
    {
    	$login_record = $this->where(['member_id'=>$member_id,'type'=>0])->order(['create_at'=>'desc'])->limit(1)->select();
        Log::record($login_record);
        if(isset($login_record[0])){
            return $login_record[0]['create_at'];
        }
        return '';
    }
}
