<?php

namespace app\blog\model;

use think\Model;

/**
 * 文章分类模型
 */
class ArticleCategory extends Model
{
    protected $table = 'article_category'; 

    /**
     * 获取所有文章分类信息
     */
    public function getCategoryList()
    {
    	$categorys = $this->field('id','title','create_at')->wher(['status'=>0])->order('create_at','asc')->select();
    	return $categorys;
    }
}
