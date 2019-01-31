<?php

namespace app\admin\model;

use think\Model;

class AdminRole extends Model
{
    protected $table = 'admin_role';

    /**
     * 关联用户表（多对多关联）
     */
    public function adminusers()
    {
    	return $this->belongsToMany('AdminUser','admin_user_role','adminuser_id','role_id')->where('status',0);
    }

    /**
     * 关联系统权限节点（多对多关联）
     */
    public function systemadminnodes()
    {
    	return $this->belongsToMany('SystemadminNode','role_systemadminnode','node_id','role_id')->where('is_RBAC',1);
    }

    /**
     * 检测角色的节点权限
     * @param  $node 节点信息
     * @param   $role_id 角色id
     * @return   true/false
     */
    public function checkRoleNode($node)
    {
    	//dump($this);
    	$nodes = $this->systemadminnodes;
    	dump($nodes);
    	foreach ($nodes as $key => $value) {
    		dump($value);
    		if($value->node == $node) return true;
    	};
    	return false;

    }

    /**
     * 修改角色状态
     * @param   $adminrole_status 当前角色状态
     */
    public function adminrolestatuschange($adminrole_status)
    {
            //判断分类状态是否已改变
            if($this->status != $adminrole_status) return ['code'=>2,'msg'=>'状态已经修改，请刷新页面再试'];
            //未改变
            $this->status == 1 ? list($msg,$status) = ['启用成功',0] : list($msg,$status) = ['禁用成功',1];
            $this->startTrans();
            try{
                $this->status = $status;
                $this->save();
                $this->commit();
                return ['code'=>0,'msg'=>$msg,'status'=>$status];
            }catch(\Exception $e){
                $this->rollback();
                return ['code'=>1,'msg'=>'状态修改失败']; 
            }
            
               
    }

    /**
     * 获取所有状态为0的角色
     */
    public function getAdminRoles()
    {
        return $this->where('status',0)->select();
    }
}
