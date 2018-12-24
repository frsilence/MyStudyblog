<?php

namespace app\blog\model;

use think\Model;
use Log;

/**
 * 应用用户模型
 */
class AppMember extends Model
{
    protected $table = 'app_member';

    /**
     * 密码修改器，保存密码时自动加密密码
     * @param string $value 密码明文
     */
    public function setPasswordAttr($value)
    {
        return password_hash($value,PASSWORD_DEFAULT);
    }

    /**
     * 性别获取器，按取出的数字代号转换为实际字符串
     * '0' -> '男', '1'-> '女'
     */
    public function getSexAttr($value)
    {
        $sex = [0=>'男',1=>'女',''=>'未知'];
        return $sex[$value];
    }

    /**
     * 所有用户基本信息获取
     */
    public function getAppMemberInfo()
    {
    	return $this->field('id,member_pid,username,userimage,phone,email,province,city,sex,status')->order('id')->select();
    }

    /**
     * 关联文章数据模型(一对多关联)
     */
    public function articles()
    {
    	return $this->hasMany('Article','member_id','id')->field('id,member_id,category_id,title,praise_num,click_num,collect_num,update_time')->where(['status'=>0,'is_delete'=>0])->order('update_time','desc');
    }

    /**
     * 关联评论数据模型(一对多关联)
     */
    public function comments()
    {
        return $this->hasMany('ArticleComment','member_id','id')->field('id,member_id,article_id,content')->where('is_delete',0)->order('update_time','desc');
    }

    /**
     * 关联用户登录记录数据模型(一对多)
     */
    public function memberloginrecords()
    {
        return $this->hasMany('AppMemberLoginRecord','member_id','id');
    }

    /**
     * 获取指定用户信息
     * @param [string] $id [用户ID]
     * @return   [array]
     */
    public function getAppMemberInfoById($id)
    {
    	$member = $this->where(['id'=>$id,'is_delete'=>0,'status'=>0])->find();
    }

    /**
     * 获取本人用户信息
     * @return   array
     */
    public function getMyMemberInfo()
    {
        if(!session('?member')) return '未登录';
        $member_info = $this->where(['id'=>session('member.id'),'status'=>0,'is_delete'=>0])->field('id,member_pid,username,userimage,phone,email,province,city,sex,create_time')->find();
        if(!empty($member_info)) {
            $member_info->articles;
            $member_info->comments;
            $member_info->memberloginrecords;
            $member_info['comment_num'] = $member_info->comments()->count();
            $member_info['article_num'] = $member_info->articles()->count();
            $member_info['follow_num'] = 10;
            $member_info['fans_num'] = 20;
            return $member_info;
        }else{
            return '其他用户';
        }

    }

    /**
     * 注册用户信息,注册成功并登陆
     * @param  $member_info [用户信息]
     * @return   [json]
     */
    public function register($member_info)
    {
    	//开启数据模型事务
    	$this->startTrans();
    	$member = $this->order('member_pid','desc')->select()->toArray();
    	empty($member) ? $member_pid = 10000 : $member_pid = $member[0]['member_pid'] + 1;
        $member_info['member_pid'] = $member_pid;
    	try{
    		$member = $this->save($member_info);
            $member_id = $this->id;
            $member = $this->where('id',$member_id)->field('id,username,email,member_pid')->find();
            $this->commit();
    		return ['code'=>0,'msg'=>'用户注册成功，正在进入系统...','member'=>$member];
    	} catch (\Exception $e){
    		//保存失败，数据库操作回滚
    		$this->rollback();
    		return ['code'=>1,'msg'=>'用户注册失败，请重试!'];
    	}
    	
    }

    /**
     * 用户登录检测
     * @param  $username 登录用户名
     * @param  $password 密码
     * @return array [用户信息]
     */
    public function login($username,$password)
    {
    	$member = $this->where(['username'=>$username,'status'=>0,'is_delete'=>0])->find();
            if(empty($member)){
                return ['code'=>1,'msg'=>'用户不存在！'];
            } 
            if(password_verify($password,$member->password)){
                $member = $member->toArray();
                unset($member['password']);
                return ['code'=>0,'msg'=>'登录成功，正在进入系统...','member'=>$member];
            }else{
                return ['code'=>1,'msg'=>'用户名密码错误！'];
            }       
    }

    /**
     * 用户信息更新
     * @param arary $update 更新信息
     * @return  boolean
     */
    public function updateMember($update)
    {
    	$member = $this->where('id',$update['id'])->find();
    	if(empty($member)) return false;
    	//开启数据库操作事务
    	$this->startTrans;
    	try{
    		$member->update($update);
    		$this->commit();
    		return true;
    	} catch(\Exception $e){
    		$this->rollback();
    		return false;
    	}
    }
    
    

}
