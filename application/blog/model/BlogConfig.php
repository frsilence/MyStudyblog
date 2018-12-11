<?php

namespace app\blog\model;

use think\Model;
/**
 * 应用配置模型
 */
class BlogConfig extends Model
{
    protected $table = 'blog_config';

    /**
     * 获取blog设置参数
     */
    public function getBlogConfig()
    {
    	$blog_config = $this->where('group','blog')->column('name,value');
    	return $blog_config;
    }
}
