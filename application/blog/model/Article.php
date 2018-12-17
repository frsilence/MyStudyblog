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
        return $this->belongsTo('AppMember','member_id','id')->field('id,username');
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
    public function category()
    {
        return $this->belongsTo('ArticleCategory','category_id','id');
    }

    /**
     * 关联评论（一对一）
     */
    public function comments()
    {
        return $this->hasOne('ArticleComment','article_id','id')->where('is_delete',0);
    }

    /**
     * 保存文章标签
     * @param  int $article_id 文章ID
     * @param  array $article_info 含有文章标签的信息 
     */
    public function saveBlogTag($article_id,$article_tags)
    {
        if(isset($article_tags) && !empty($article_tags)){
            $tag_list = explode(';',$article_tags);
            $blog_tag = [];
            foreach ($tag_list as $key => $value) {
                $tag_id = model('BlogTag')->where('name',$value)->value('id');
                if(empty($tag_id)){
                    $tag = model('BlogTag')->create(['name'=>$value]);
                    $tag_id = $tag->id;
                }
                $blog_tag[] = ['article_id'=>$article_id,'tag_id'=>$tag_id];
            }
            model('ArticleTag')->saveAll($blog_tag);

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
    		$article=$this->save([
                    'member_id' => session('member.id'),
                    'category_id' => $article_info['article_category'],
                    'update_userid' => session('member.id'),
                    'title' => $article_info['article_title'],
                    'content' => $article_info['article_content'],
                ]);
    		$article_id = $this->id;
            //保存标签
            $this->saveBlogTag($article_id,$article_info['article_tag']);
    		$this->commit();
    	} catch(\Exception $e){
    		$this->rollback();
    		return 0;
    	}
    	return $article_id = $article_id;

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
            } catch(\Exception $e){
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
            }catch(\Exception $e){
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
        if(empty($article_info)){
            $article_info[] = $article_info->member();
            $article_info[] = $article_info->categorys();
            $article_info[] = $article_info->tags();
            $article_info[] = $article_info->comments->count();
        }
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
        });
        return $articles;
    }

    /**
     * 获取最新文章
     * @param int $limit 获取数量
     * @return think\Collection [description]
     */
    public function getLatestArticle($limit = 10)
    {
        $latest_article = $this->where(['status'=>0,'is_delete'=>0])->limit($limit)->order('update_time','desc')->select()->each(function($item,$key){
                $item->member;
                $item->category;
                $item->tags;
                //$item['comment_num'] = $item->comments->count();
        });
        return $latest_article;
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
    public function getLastArticle($article_id)
    {
        $next_article = $this->where(['status'=>0,'is_delete'=>0,'id'<$article_id])->order('id','desc')->find();
        return $last_article; 
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
