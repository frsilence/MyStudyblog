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
}
