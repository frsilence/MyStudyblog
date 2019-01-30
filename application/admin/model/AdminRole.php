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
}
