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
        return $this->belongsToMany('Article','article_tag','tag_id','article_id');                                                         
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
        return $this->hasOne('ArticleComment','article_id','id')->where('is_delete'=>0);
    }

    /**
     * 保存文章标签
     * @param  int $article_id 文章ID
     * @param  array $article_info 含有文章标签的信息 
     */
    public function saveBlogTag($article_id,$article_info)
    {
        if(isset($article_info['tag_list']) && !empty($article_info['tag_list'])){
            $tag_list = explode(',',$article_info['tag_list']);
            $article_tag = [];
            foreach ($tag_list as $key => $value) {
                $tag_id = model('BlogTag')->where('name',$value)->value('id');
                if(empty($tag_id)){
                    $tag = model('BlogTag')->save(['name'=>$value]);
                    $tag_id = $tag->id;
                }
                $article_tag[] = ['article_id'=>$article_id,'tag_id'=>$tag_id];
            }
            model('ArticleTag')->saveAll($article_tag);

        }
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
            $this->saveBlogTag($article_id,$article_info);
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
            $this->startTrans();
            try{
                $article->is_delete = 1;
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
                $this->saveBlogTag($article_id,$update);
                $this->commit();
                return true;
            }catch(/Exception $e){
                $this->rollback();
                return false;
            }           
        }
    }

    /**
     * 查询：根据文章ID查询文章内容
     * @param  int $article_id [文章ID]
     * @return  array
     */
    public function getArticleByArticleId($article_id)
    {
        $article_info = $this->where(['id'=>$article_id,'status'=>0,'is_delete'=>0])->find();
        $article_info['member'] = $article_info->member();
        return $article_info;
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

    /**
     * 获取当前文章的下一篇文章
     * @param  int $article_id 当前文章ID
     * @return /think/model
     */
    public function getNextArticle($article_id)
    {
        $next_article = $this->where(['status'=>0,'is_delete'=>0,'id'>$article_id])->order('id','asc')->find();
        return $next_article; 
    }

    /**
     * 获取当前文章的上一篇文章
     * @param  int $article_id 当前文章ID
     * @return /think/model
     */
    public function getNextArticle($article_id)
    {
        $next_article = $this->where(['status'=>0,'is_delete'=>0,'id'<$article_id])->order('id','desc')->find();
        return $next_article; 
    }

    /**
     * 搜索文章标题
     * @param string $search_word
     * @return  /think/Paginator
     */
    public function searchArticle($search_word)
    {
        $search_list = [
            ['status','=',0],
            ['is_delete','=',0],
            ['title','LIKE',"{$search_word}"]];
        $articles = $this->where(['category_id'=>$category_id,'status'=>0,'is_delete'=>0])->paginator($page)->each(function($item,$key){
                $item->member();
                $item->categorys();
                $item->tags();
                $item['comment_num'] = $item->comments()->count();

        });
        return $articles;
    }


}
