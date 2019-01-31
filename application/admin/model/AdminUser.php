<?php

namespace app\admin\model;

use think\Model;

class AdminUser extends Model
{
    protected $table = 'admin_user';

    /**
     * 关联用户表（多对多关联）
     */
    public function adminroles()
    {
        return $this->belongsToMany('AdminRole','admin_user_role','role_id','adminuser_id')->where('status',0);
    }
    

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
        $sex = [0=>'男',1=>'女'];
        return $sex[$value];
    }

    /**
     * 性别修改器
     * '男'=>0 ,'女'=>1,'未知'=>''
     */
    public function setSexAttr($value)
    {
        $sex = ['男'=>0,'女'=>1];
        return $sex[$value];
    }

    /**
     * 权限判断
     * @param string $node 节点信息
     * @param  $user_id 用户id
     * @return  true/false
     */
    public function checknode($node)
    {
        $roles = $this->adminroles;
        foreach ($roles as $key => $value) {
            if($value->checkRoleNode($node)) return true;
        };
        return false;
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
    	$member_info = $this->where(['id'=>$id,'is_delete'=>0,'status'=>0])->field('id,member_pid,username,userimage,phone,email,province,city,sex,create_time')->find();
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
            return '用户不存在';
        }

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
            return '用户不存在';
        }

    }

    /**
     * 注册用户信息
     * @param  $adminuser_info [用户信息]
     * @return   [json]
     */
    public function register($adminuser_info)
    {
        $role_id = $adminuser_info['adminrole_id'];
    	//开启数据模型事务
    	$this->startTrans();
    	try{
    		$adminuser = $this->save(['username'=>$adminuser_info['newusername'],'email'=>$adminuser_info['email'],'password'=>$adminuser_info['password']]);
            $adminuser_id = $this->id;
            //添加角色-用户关系
            $this->adminroles()->save($role_id);
            $this->commit();
    		return ['code'=>0,'msg'=>'管理员账号添加成功','adminuser'=>$adminuser];
    	} catch (\Exception $e){
    		//保存失败，数据库操作回滚
    		$this->rollback();
    		return ['code'=>1,'msg'=>'管理员账户添加失败，请重试!'];
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
    	$adminuser = $this->where(['username'=>$username,'status'=>0,'is_delete'=>0])->find();
            if(empty($adminuser)){
                return ['code'=>1,'msg'=>'用户不存在！'];
            } 
            if(password_verify($password,$adminuser->password)){
                $adminuser = $adminuser->toArray();
                unset($adminuser['password']);
                return ['code'=>0,'msg'=>'登录成功，正在进入系统...','adminuser'=>$adminuser];
            }else{
                return ['code'=>1,'msg'=>'用户名密码错误！'];
            }       
    }

    /**
     * 用户信息更新
     * @param arary $update 更新信息
     * @return  boolean
     */
    public function updateInfoForm($id,$update)
    {
    	$member = $this->where('id',$id)->find();
    	if(empty($member)) return ['code'=>1,'用户不存在'];
    	//开启数据库操作事务
    	$this->startTrans();
    	try{
    		$member->save([
                'phone' => $update['phone'],
                'province' => $update['province'],
                'city' => $update['city'],
                'sex' => $update['sex'],
                ]);
    		$this->commit();
    		return ['code'=>0];
    	} catch(\Exception $e){
    		$this->rollback();
    		return ['code'=>1];
    	}
    }

    /**
     * 设置用户头像
     */
    public function updateUserimage($id,$user_imageurl)
    {
        $member = $this->where('id',$id)->find();
        if(empty($member)) return ['code'=>1,'用户不存在'];
        $this->startTrans();
        try{
            $member->userimage=$user_imageurl;
            $member->save();
            $this->commit();
            return ['code'=>0,'msg'=>'头像更新成功'];
        } catch(\Exception $e){
            $this->rollback();
            return ['code'=>1,'msg'=>'头像更新失败，请稍后尝试'];
        }

    }
}
