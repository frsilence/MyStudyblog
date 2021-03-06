<?php

namespace app\blog\model;

use think\Model;
use think\Db;
use think\facade\Request;
use app\blog\model\ArticleComment;

/**
 * 文章数据表模型
 */

class Article extends Model
{
    protected $table = 'article';

    /**
     * 关联用户表单（一对多关联）
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
        return $this->belongsToMany('BlogTag','article_tag','tag_id','article_id')->where(['status'=>0]);                                                         
    }

    /**
     * 关联文章分类(一对多)
     */
    public function category()
    {
        return $this->belongsTo('ArticleCategory','category_id','id')->field('id,category_title');
    }

    /**
     * 关联评论（一对多）
     */
    public function comments()
    {
        return $this->hasMany('ArticleComment','article_id','id')->where('is_delete',0)->order('create_time','desc');
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
     * 管理员保存文章标签
     * @param  int $article_id 文章ID
     * @param  array $article_info 含有文章标签的信息 
     */
    public function saveBlogTagByAdmin($article_id,$article_tags)
    {
        if(isset($article_tags) && !empty($article_tags)){
            $tag_list = explode(';',$article_tags);
            $blog_tag = [];
            foreach ($tag_list as $key => $value) {
                $tag_id = model('blog/BlogTag')->where('name',$value)->value('id');
                if(empty($tag_id)){
                    $tag = model('blog/BlogTag')->create(['name'=>$value]);
                    $tag_id = $tag->id;
                }
                $blog_tag[] = ['article_id'=>$article_id,'tag_id'=>$tag_id];
            }
            model('blog/ArticleTag')->saveAll($blog_tag);

        }
    }

    /**
     * 管理员更新文章标签
     * @param  int $article_id 文章ID
     * @param  array $article_info 含有文章标签的信息 
     */
    public function updateBlogTag($article_id,$article_tags)
    {
        if(isset($article_tags) && !empty($article_tags)){
            $tag_list = explode(';',$article_tags);
            $blog_tag = [];
            foreach ($tag_list as $key => $value) {
                if($value!=''){
                    $tag_id = model('blog/BlogTag')->where('name',$value)->value('id');
                    if(empty($tag_id)){
                        $tag = model('blog/BlogTag')->create(['name'=>$value]);
                        $tag_id = $tag->id;
                    }
                    $blog_tag[] = ['article_id'=>$article_id,'tag_id'=>$tag_id];
                }
                
            }
            //删除该文章全部标签
            model('blog/ArticleTag')->where('article_id',$article_id)->delete();
            //保存最新标签
            model('blog/ArticleTag')->saveAll($blog_tag);

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
     * 管理员增加文章
     * @param  array $article_info 文章信息
     * @return  int article_id
     */
    public function addArticleByAdmin($article_info)
    {
    	$this->startTrans();
    	try{
    		$article=$this->save([
                    'member_id' => 1,
                    'category_id' => $article_info['article_category'],
                    'update_userid' => 1,
                    'title' => $article_info['article_title'],
                    'content' => $article_info['article_content'],
                ]);
    		$article_id = $this->id;
            //保存标签
            $this->saveBlogTagByAdmin($article_id,$article_info['article_tag']);
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
     * @param arary $update 文章更新信息（包含文章id）
     * return boolenan
     */
    public function updateArticle($update)
    {
        $article=$this->where(['id'=>$update['article_updateid'],'is_delete'=>0])->find();
        if(empty($article)){
            return ['code'=>1,'msg'=>'被更新文章不存在，请稍后重试'];
        }else{
            $this->startTrans();
            try{
                $article->title=$update['article_title'];
                $article->category_id = $update['article_category'];
                $article->content = $update['article_content'];
                $article->save();
                $this->updateBlogTag($update['article_updateid'],$update['article_tag']);
                $this->commit();
                return ['code'=>0,'msg'=>'文章更新成功'];
            }catch(\Exception $e){
                $this->rollback();
                return ['code'=>1,'msg'=>'未知错误，文章更新失败'];
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
        if(!empty($article_info)){
            $article_info->member;
            $article_info->category;
            $article_info->tags;
            $article_info->comments->each(function($item,$key){$item->member;});
            $article_info['comment_num']=$article_info->comments()->count();
        }
        return $article_info;
    }

    /**
     * 查询：根据用户ID查询其拥有的文章
     * @param  int $member_id [用户ID]
     * @return  /think/Paginator
     */
    public function getMemberArticleList($member_id)
    {
        $article_list = $this->where(['member_id'=>$member_id,'status'=>0,'is_delete'=>0])->field('id,member_id,category_id,title,praise_num,click_num,collect_num,update_time')->order('update_time','desc')->paginate(request()->param('list_rows'),false,['var_page' => 'page','query'=>request()->param()])->each(function($item,$key){
                $item->member;
                $item->category;
                $item['comment_num'] = $item->comments()->count();
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
                $item['member_url'] = url('blog/member/readMember',['id'=>$item['member_id']]);
                $item['category_url'] = url('blog/article/getCategory',['id'=>$item['category_id']]);
        });
        return $article_list;
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
                $item['comment_num'] = $item->comments()->count();
        });
        return $latest_article;
    }


    /**
     * 查询：根据文章分类进行 查询
     * @param int $category_id 分类ID
     * @param int $page
     * @return /think/Paginator
     */
    public function getArticleByCategoryId($id)
    {
        $articles = model('Article')->where(['category_id'=>$id,'status'=>0,'is_delete'=>0])->field('id,member_id,category_id,title,praise_num,click_num,collect_num,update_time')->paginate(request()->param('list_rows'))->each(function($item,$key){
                $item->member;
                $item->category;
                $item['comment_num'] = $item->comments()->count();
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
                $item['member_url'] = url('blog/member/readMember',['id'=>$item['member_id']]);
                $item['category_url'] = url('blog/article/getCategory',['id'=>$item['category_id']]);

        });
        return json($articles);
    }

    /**
     * 获取当前文章的下一篇文章
     * @param  int $article_id 当前文章ID
     * @return /think/model
     */
    public function getNextArticle($article_id)
    {
        $next_article = $this->where([
            ['status','=',0],
            ['is_delete','=',0],
            ['id','>',$article_id]
            ])->order('id','asc')->field('id,title')->find();
        return $next_article; 
    }

    /**
     * 获取当前文章的上一篇文章
     * @param  int $article_id 当前文章ID
     * @return /think/model
     */
    public function getLastArticle($article_id)
    {
        $last_article = $this->where([
            ['status','=',0],
            ['is_delete','=',0],
            ['id','<',$article_id]
            ])->order('id','desc')->field('id,title')->find();
        return $last_article; 
    }

    /**
     * 搜索文章标题
     * @param string $search_word 
     * @return  /think/Paginator
     */
    public function searchArticle($search_list,$search_word)
    {
        $articles = $this->where($search_list)->field('id,member_id,category_id,title,praise_num,click_num,collect_num,update_time')->order('update_time','desc')->paginate(request()->param('list_rows'),false,['var_page' => 'page','query'=>request()->param()])->each(function($item,$key){
                $item->member;
                $item->category;
                $item['comment_num'] = $item->comments()->count();
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
                $item['member_url'] = url('blog/member/readMember',['id'=>$item['member_id']]);
                $item['category_url'] = url('blog/article/getCategory',['id'=>$item['category_id']]);

        });
        //替换被查询字段的颜色
        foreach ($articles as $key => $value) {
            $value['title'] = $this->replaceTitle($search_word,$value['title']);
        }
        return $articles;
    }

    /**
     *更新文章的点赞数量
     *@param int $article_id 文章id
     *@param int $add_praise_ounts 增加的点赞数量
     *@return  
     */
    public function updateArticlePraiseNum($article_id,$add_praise_ounts)
    {
        $article = $this->where(['id'=>$article_id])->find();
        if(!empty($article)){
            $this->startTrans();
                try{
                    $article->praise_num = $article->praise_num +$add_praise_ounts;
                    $article->save();
                    $this->commit();
                    return true;
                        
                }catch(\Exception $e){
                    $this->rollback();
                    return false;
                }
        }    
                
    }

    /**
     * 替换字体颜色
     * @param $word
     * @param $title
     * @return mixed
     */
    protected function replaceTitle($word, $title) {
        $format_word = "<span style='color:red'>{$word}</span>";
        $title = str_ireplace($word, $format_word, $title);
        return $title;
    }

    /**
     * 获取相关文章
     */
    public function getRelatedArticle($article_id,$limit=6)
    {
        //初始化
        list($articleList, $whereTagIn, $whereArticleIn, $whereArticleCateNotIn, $whereArticleNotIn, $order) = [
            [], [], [], [], [],
            ['click_num' => 'desc', 'recommend' => 'desc', 'praise_num' => 'desc',  'create_time' => 'desc'],
        ];

        /**
         * 1、根据文章标签查找计算相关帖子
         */
        $tagList = Db::name('ArticleTag')->where(['article_id' => $article_id])->distinct(true)->field('tag_id')->select();
        if (!empty($tagList)) {
            foreach ($tagList as $vo) $whereTagIn[] = $vo['tag_id'];

            $articleIdTagList = Db::name('ArticleTag')->whereIn('tag_id', $whereTagIn)->distinct(true)->field('article_id')->select();

            foreach ($articleIdTagList as $vo) $whereArticleIn[] = $vo['article_id'];

            $articleList = Db::name('Article')->field('id,title')->whereNotIn('id', $article_id)->whereIn('id', $whereArticleIn)
                ->where(['status' => 0, 'is_delete' => 0])->order($order)->limit($limit)->select();
        }

        /**
         * 2、如果根据标签查找出的文章数量不够限制数据，将根据该文章分类下的所有文章的点击量、评论量、点赞量、更新时间的查找出对应的帖子进行补充
         */
        if (count($articleList) <= $limit) {
            foreach ($articleList as $vo) $whereArticleCateNotIn[] = $vo['id'];
            $whereArticleCateNotIn = $article_id;
            $category_id = Db::name('Article')->where(['id' => $article_id])->value('category_id');

            $articleOtherList = Db::name('Article')->field('id,title')->whereNotIn('id', $whereArticleCateNotIn)
                ->where(['status' => 0, 'is_delete' => 0, 'category_id' => $category_id,])->order($order)->limit($limit)->select();

            foreach ($articleOtherList as $vo) {
                if (count($articleList) >= $limit) break;
                $articleList[] = $vo;
            }
        }

        /**
         * 3、如果根据标签查找出的文章数量不够限制数据，将根据全部分类文章的点击量、评论量、点赞量、更新时间的查找出对应的帖子进行补充
         */
        if (count($articleList) <= $limit) {
            foreach ($articleList as $vo) $whereArticleNotIn[] = $vo['id'];
            $whereArticleNotIn = $article_id;

            $articleOtherList = Db::name('Article')->field('id,title')->whereNotIn('id', $whereArticleNotIn)->where(['status' => 0, 'is_delete' => 0])
                ->order($order)->limit($limit)->select();

            foreach ($articleOtherList as $vo) {
                if (count($articleList) >= $limit) break;
                $articleList[] = $vo;
            }
        }

        //返回结果
        foreach ($articleList as &$value) {
            $value['article_url'] = url('blog/article/readArticle',['id'=>$value['id']]);
        }
        return $articleList;
    }

    /**
     * 获取点赞文章列表
     */
    public function getPraiseArticleList($category_id,$limit = 8)
    {
        if($category_id === 0){
            $praisearticlelist = $this->where(['status'=>0,'is_delete'=>0])->field('id,title,praise_num')->order('praise_num','desc')->limit($limit)->select()->each(function($item,$key){
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
            });
        }else{
            $praisearticlelist = $this->where(['status'=>0,'is_delete'=>0,'category_id'=>$category_id])->field('id,title,praise_num')->order('praise_num','desc')->limit($limit)->select()->each(function($item,$key){
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
            });
        }
        
        return $praisearticlelist;
    }

    /**
     * 获取阅读量文章列表
     */
    public function getClickArticleList($category_id,$limit=8)
    {
        if($category_id === 0){
            $clickarticlelist = $this->where(['status'=>0,'is_delete'=>0])->field('id,title,click_num')->order('click_num','desc')->limit($limit)->select()->each(function($item,$key){
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
            });
        }else{
            $clickarticlelist = $this->where(['status'=>0,'is_delete'=>0,'category_id'=>$category_id])->field('id,title,click_num')->order('click_num','desc')->limit($limit)->select()->each(function($item,$key){
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
            });
        }
        
        return $clickarticlelist;
    }

    /**
     * 删除文章
     * @param array $articleid_list 被删除文章的id集合（数组）
     */
    public function deleteBlogArticle($articleid_list)
    {
        $this->startTrans();
        try{
            foreach ($articleid_list as $key => $value) {
                //删除文章
                $article = $this->where('id',$value)->find();
                $article->is_delete = 1;
                $article->save();
                //删除该文章下的评论
                $article_comment = new ArticleComment();
                $article_comments = $article_comment->where(['article_id'=>$value])->select();
                foreach ($article_comments as $key => $value) {
                    $value->is_delete = 1;
                    $value->save();
                }
                //删除该文章的标签
                //暂时不删
                
            }
            $this->commit();
            return ['code'=>0,'msg'=>'删除文章成功'];
        }catch(\Exception $e){
            $this->rollback();
            return ['code'=>1,'msg'=>'删除文章失败'];
        }
    }


}
