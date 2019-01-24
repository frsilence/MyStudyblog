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
    /**
     * 新增分类
     * @param $category_title 新的分类标题
     * @param $category_content 新的分类简介
     */
    public function addBlogCategory($category_title,$category_content)
    {
    	try{
    		$category = $this->save([
    			'category_title'=>$category_title,
    			'category_content'=>$category_content,
    			'category_image'=>'\static\image\test.PNG',
    			'status'=>0,
    			]);
    		$category_id = $this->id;
    		return ['code'=>0,'msg'=>'新增分类成功','category_id'=>$category_id];
    	}catch(\Exception $e){
    		return ['code'=>1,'msg'=>'新增分类失败'];
    	}
    }
}
