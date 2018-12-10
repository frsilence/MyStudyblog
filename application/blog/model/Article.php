<?php

namespace app\blog\model;

use think\Model;

/**
 * 文章数据表模型
 */

class Article extends Model
{
    protected $table = 'article';

    /**
     * 关联用户表单（一对一关联）
     */
    public function member()
    {
        return $this->belongsTo('AppMember','member_id','id')->field('id','username');
    }

    /**
     * 关联文章标签数据表(多对多)
     */
    public function tags()
    {
        return $this->belongsToMany('Article','article_tag','member_id','article_id');                                                         
    }

    /**
     * 关联文章分类(一对多)
     */
    public function categorys()
    {
        return $this->belongsTo('ArticleCategory','category_id','id');
    }

    /**
     * 关联评论（一对一）
     */
    public function comments()
    {
        return $this->hasOne('ArticleComment','article_id','id');
    }

    /**
     * 增加文章
     * @param  array $article_info 文章信息
     * @return  int article_id
     */
    public function addArticle($article_info)
    {
    	$this->startTrans();
    	try{
    		$article=$this->save($article_info);
    		$article_id = $this->article_id;
            //保存标签
            foreach ($article_info['tag'] as $key => $value) {
                $article->tags()->save(['name'=>$value,'status'=>0]);
            }
    		$this->commit();
    	} catch(/Exception $e){
    		$this->rollback;
    		return false;
    	}
    	$article_id = $this->article_id;

    }

    /**
     * 删除文章
     * @param int $article_id 文章id
     * @param boolean
     */
    public function deleteArticle($article_id)
    {
        $article=$this->where(['id'=>$article_id,'status'=>0,'is_delete'=>0])->find();
        if(empty($article)){
            return false;
        }else{
            $article->is_delete = 1;
            $this->startTrans();
            try{
                $article->save();
                $this->commit();
                return true;
            } catch(/Exception $e){
                $this->rollback();
                return false;
            }
        }
    }

    /**
     * 修改文章
     * @param int $article_id 文章ID
     * @param arary $update 修改内容
     * return boolenan
     */
    public function updateArticle($article_id,$update)
    {
        $article=$this->where(['id'=>$article_id,'status'=>0,'is_delete'=>0])->find();
        if(empty($article)){
            return false;
        }else{
            $this->startTrans();
            try{
                $article->update($update);
                $this->commit();
                return true;
            }catch(/Exception $e){
                $this->rollback();
                return false;
            }           
        }
    }

    /**
     * 查询：根据用户ID查询其拥有的文章
     * @param  int $member_id [用户ID]
     * @return  /think/Paginator
     */
    public function getArticleByMemberId($member_id,$page=10)
    {
        $articles = $this->where(['member_id'=>$member_id,'status'=>0,'is_delete'=>0])->paginator($page)->each(function($item,$key){
                $item->member();
                $item->categorys();
                $item->tags();
                $item['comment_num'] = $item->comments()->count();
        })
        return $articles;
    }

    /**
     * 查询：根据文章分类进行 查询
     * @param int $category_id 分类ID
     * @param int $age
     * @return /think/Paginator
     */
    public function getArticleByCategoryId($category_id,$page=10)
    {
        $search_list = ['status'=>0,'is_delete'=>0];
        if($category_id!=0) $search_list['category_id'] = $category_id;
        $articles = $this->where(['category_id'=>$category_id,'status'=>0,'is_delete'=>0])->paginator($page)->each(function($item,$key){
                $item->member();
                $item->categorys();
                $item->tags();
                $item['comment_num'] = $item->comments()->count();

        });
        return $articles;
    }


}
