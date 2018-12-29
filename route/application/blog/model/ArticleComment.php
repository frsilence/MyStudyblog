<?php

namespace app\blog\model;

use think\Model;

/**
 * 文章评论模型
 */
class ArticleComment extends Model
{
    protected $table = 'article_comment';

    /**
     * 关联文章模型（一对多）
     */
    public function article()
    {
    	return $this->belongsTo('Article','article_id','id')->field('id,title,update_time')->where(['status'=>0,'is_delete'=>0]);
    }

    /**
     * 关联用户模型(一对多)
     */
    public function member()
    {
    	return $this->belongsTo('AppMember','member_id','id')->field('id,username,userimage');
    }

    /**
     * 根据文章ID获取评论
     * @param  int $article_id 文章ID
     */
    public function getCommentByArticleId($article_id)
    {
    	$comments = $this->where(['article_id'=>$article_id,'is_delete'=>0])->order('create_at','asc')->select()->each(function($item,$key){
    			$item->member();
    	});
    	return $comments;
    }

    /**
     * 根据用户id获取评论
     * @param int $member_id 用户ID
     * @return \think\Collection 
     */
    public function getMemberCommentList($request,$member_id)
    {
        $comment_list = $this->where(['member_id'=>$member_id,'is_delete'=>0])->field('id,member_id,article_id,content,update_time')->order('update_time','desc')->paginate($request->param('list_rows'),false,['var_page' => 'page','query'=>$request->param()])->each(function($item,$key){
                $item->member;
                $item->article;
                $item['article_url'] = url('blog/article/readArticle',['id'=>$item->article_id]);
        });
        return $comment_list;
    }

    /**
     * 增加评论
     * @param  arary $comment_info
     * @return  int
     */
    public function addComment($comment_info)
    {
        $this->startTrans();
        try{
            $this->save([
                'member_id'=>$comment_info['member_id'],
                'article_id'=>$comment_info['article_id'],
                'content'=>$comment_info['comment_content'],
                ]);
            $this->commit();
            return 0;
        }catch(\Exception $e){
            $this->rollback();
            return 1;
        }
    }
}
