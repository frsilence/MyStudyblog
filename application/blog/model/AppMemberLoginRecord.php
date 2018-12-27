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
     * 字段login_type登陆类型字段获取器
     * 0=>'登陆账号';1=>'主动退出';2=>'登陆过期'
     */
    public function getTypeAttr($value)
    {
        $login = [0=>'登陆账号',1=>'主动退出',2=>'登陆过期'];
        return $login[$value];
    }

    /**
     * 获取当前会话用户登录记录
     * @reurn \think\Paginator 
     */
    public function getMemberSelfLoginRecord()
    {
    	if(session('?member.id')){
            $record_list = $this->where(['member_id'=>session('member.id')])->field('id,member_id,type,login_ip,login_area,remark,create_time')->order(['create_time'=>'desc'])->paginate(request()->param('list_rows'),false,['var_page' => 'page','query'=>request()->param()])->each(function($item,$key){
                $item['username'] = model('AppMember')->where(['id'=>$item->member_id])->find()->username;
            });
            return ['code'=>0,'msg'=>'获取成功','login_record'=>$record_list];
        }
        else{
            return ['code'=>1,'msg'=>'未登录或者用户不存在'];
        }
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
