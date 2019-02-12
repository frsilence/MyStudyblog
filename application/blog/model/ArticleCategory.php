<?php

namespace app\blog\model;

use think\Model;
use app\blog\model\Article;

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
    	$categorys = $this->field('id,category_title,create_time')->where(['status'=>0])->order('create_time','asc')->select();
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

    /**
     * 删除分类
     * @param array $categoryid_list 被删除分类的id集合（数组）
     */
    public function deleteBlogCategory($categoryid_list)
    {
        $this->startTrans();
        try{
            foreach ($categoryid_list as $key => $value) {
                //删除分类
                $this->where('id',$value)->delete();
                //禁用分类下的文章
                $article = new Article();
                $articles = $article->where(['category_id'=>$value])->select();
                foreach ($articles as $key => $value) {
                    $value->status = 1;
                    $value->save();
                }
            }
            $this->commit();
            return ['code'=>0,'msg'=>'删除分类成功'];
        }catch(\Exception $e){
            $this->rollback();
            return ['code'=>1,'msg'=>'删除分类失败'];
        }
    }
}
