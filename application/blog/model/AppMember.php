<?php

namespace app\blog\model;

use think\Model;

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
     * 所有用户基本信息获取
     */
    public function getAppMemberInfo()
    {
    	return $this->field('id,member_pid,username,userimage,phone,email,province,city,sex,status')->order('id')->select();
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
     * 注册用户信息,注册成功并登陆
     * @param  $member_info [用户信息]
     * @return   [json]
     */
    public function register($member_info)
    {
    	//开启数据模型事务
    	$this->startTrans();
    	//$member_pid = $this->cloumn('member_pid')->select();
    	//empty($member_pid) ? $member_pid = 10000 : $member_pid = $member_pid + 1;
    	try{
    		$member = $this->save($member_info);
    		$this->commit();
    		return $member;
    	} catch (\Exception $e){
    		//保存失败，数据库操作回滚
    		$this->rollback();
    		return $member=[];
    	}
    	
    }

    /**
     * 用户登录检测
     * @param  $email 登录邮箱
     * @param  $password 密码
     * @return array [用户信息]
     */
    public function login($email,$password)
    {
    	$member = $this->where('email',$email)->find();
            if($member !== null && password_verify($password,$member->password)){
                return $member;
            }else{
                return $member=[];
            }
    }

    /**
     * 用户信息更新
     * @param arary $update 更新信息
     * @return  boolean
     */
    public function update($update)
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
