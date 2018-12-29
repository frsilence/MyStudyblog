<?php

namespace app\blog\model;

use think\Model;

/**
 * 应用首页轮播表模型
 */

class BlogSilder extends Model
{
    protected $table = 'blog_silder';

    /**
     * 获取轮播表
     */
    public function getBlogSilder()
    {
    	$blog_silder = $this->where('status',0)->order('sort','asc')->select();
    	return $blog_silder;
    }
}
