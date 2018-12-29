<?php

namespace app\blog\model;

use think\Model;

/**
 * 用户-关注文章模型
 */
class ArticleMember extends Model
{
    protected $table = 'article_member';

    /**
     * 检测用户是否关注文章
     * @param int $artcicle_id 文章id
     * @param int $member_id 用户id
     * @return json [description]
     */
    public function checkArticleMember($article_id,$member_id)
    {
        if(empty($this->where(['article_id'=>$article_id,'member_id'=>$member_id,'is_delete'=>0])->find())){
            return ['code'=>0,'msg'=>'未关注'];
        }else{
            return ['code'=>1,'msg'=>'已关注'];
        }
    }

    /**
     * 用户关注（收藏）指定文章
     * @param  int $member_id 用户id
     * @param  int $article_id 文章id
     * @return  string (0->已关注，1->用户或者文章不存在，2->关注成功，3->关注失败)
     */
    public function addArticleToMember($member_id,$article_id)
    {
    	$articleMember = $this->where(['member_id'=>$member_id,'article_id'=>$article_id,'is_delete'=>0])->find();
    	if(!empty($articleMember)){
    		return ['code'=>2,'msg'=>'已关注，重复操作'];
    	}else{
    		if(empty(model('Article')->where(['id'=>$article_id,'status'=>0,'is_delete'=>0])->find()) || empty(model('AppMember')->where(['id'=>$member_id,'status'=>0,'is_delete'=>0])->find())){
    			return ['code'=>1,'msg'=>'关注失败，刷新重试'];
    		}
    	};
    	$articleMember = $this->where(['member_id'=>$member_id,'article_id'=>$article_id,'is_delete'=>1])->find();
    	$this->startTrans();
    	try{
    		if(empty($articleMember)){
    			$this->save(['member_id'=>$member_id,'article_id'=>$article_id]);
    		}else{
    			$articleMember->is_delete = 0;
    			$articleMember->save();
    		}
    		$this->commit();
    		return ['code'=>0,'msg'=>'关注成功'];
    	}catch(\Exception $e){
    		$this->rollback();
    		return ['code'=>1,'msg'=>'关注失败，刷新重试'];
    	}
    }

    /**
     * @param  int $member_id 用户id
     * @param  int $article_id 文章id
     * @return  string (0->未关注，2-取消关注成功，3->取消关注失败)
     */
    public function deleteArticleToMember($member_id,$article_id)
    {
    	$articleMember = $this->where(['member_id'=>$member_id,'article_id'=>$article_id,'is_delete'=>0])->find();
    	if(empty($articleMember)){
    		return 0;
    	}else{
    		$this->startTrans();
    		try{
    			$articleMember->is_delete = 1;
    			$articleMember->save();
    			$this->commit();
    			return 2;
    		}catch(\Exception $e){
    			$this->rollback();
    			return 3;
    		}
    		
    	}
    }

    /**
     * 获取指定用户的关注文章列表
     * @param  int $article_id 文章id
     * @param int $page 每页数量
     * @return  /think/Paginate
     */
    public function getArticleFollowerByMemberId($member_id,$page=10)
    {
    	$articles = $this->where(['member_id'=>$member_id,'is_delete'=>0])->paginate($page)->each(function($item,$key){
    		$item['article_info'] = model('Article')->where(['id'=>$item['article_id'],'status'=>0,'is_delete'=>0])->find();
    	});
        return $articles;
    }
    
}
