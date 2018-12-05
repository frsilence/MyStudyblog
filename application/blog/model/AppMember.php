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
     * 所有用户基本信息获取
     */
    public function getAppMemberInfo()
    {
    	return $this->field('id,member_pid,username,userimage,phone,email,province,city,sex,status')->find();
    }
}
