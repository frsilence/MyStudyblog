<?php

namespace app\blog\model;

use think\Model;

/**
 * 应用通知模型
 */
class BlogAnnouncement extends Model
{
    protected $table = 'blog_announcement';

    /**
     * 获取应用通知
     */
    public function getAnnouncement()
    {
    	$list = $this->where('status',0)->order('sort','asc')->select();
    	return $list;
    }
}
