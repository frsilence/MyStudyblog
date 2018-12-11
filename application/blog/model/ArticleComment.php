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
    	return $this->belongsTo('Article','article_id','id')->where(['status'=>0,'is_delete'=>0]);
    }

    /**
     * 关联用户模型(一对多)
     */
    public function member()
    {
    	return $this->belongsTo('Member','member_id','id')->field('id','username');
    }

    /**
     * 根据文章ID获取评论
     * @param  int $article_id 文章ID
     */
    public getCommentByArticleId($article_id)
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
    public function getCommentByMemberId($member_id,$page=15)
    {
        $comment_list = $this->where(['member_id'=>$member_id,'is_delete'=>0])->order('create_at','desc')->paginate($page)->each(function($item,$key){
            $item['article_info'] = $this->articlee()->field('title','id');
        })
    }

    /**
     * 增加评论
     * @param  arary $comment_info
     * @return  boolean [description]
     */
    public function addComment($comment_info)
    {
        $this->startTrans();
        try{
            $this->save($comment_info);
            $this->commit();
            return true;
        }catch{
            $this->rollback();
            return false;
        }
    }
}
