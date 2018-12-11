<?php

namespace app\blog\model;

use think\Model;

/**
 * 文章标签模型
 */
class BlogTag extends Model
{
    protected $table = 'blog_tag';

    /**
     * 关联文章模型(多对多)
     */
    public function articles()
    {
        return $this->belongsToMany('Article','article_tag','article_id','tag_id');                                                         
    }

    /**
     * 获取所有标签
     */
    public function getAllTag()
    {
    	$tag_list = $this->where('status'=>0)->select();
    	return $tag_list;
    }

}
