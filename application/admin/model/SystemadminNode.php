<?php

namespace app\admin\model;

use think\Model;

class SystemadminNode extends Model
{
    protected $table = 'systemadmin_node';
    /**
     * 关联系统权限节点（多对多关联）
     */
    public function roles()
    {
    	return $this->belongsToMany('AdminRole','role_systemadminnode','role_id','node_id');
    }
}
