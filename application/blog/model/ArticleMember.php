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
     * @return  string (0->关注成功，1->关注失败，2->已关注)
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
     * @return  string (0->取消成功，1->取消关注失败，2->未关注，)
     */
    public function deleteArticleToMember($member_id,$article_id)
    {
    	$articleMember = $this->where(['member_id'=>$member_id,'article_id'=>$article_id,'is_delete'=>0])->find();
    	if(empty($articleMember)){
    		return ['code'=>2,'msg'=>'未关注'];
    	}else{
    		$this->startTrans();
    		try{
    			$articleMember->is_delete = 1;
    			$articleMember->save();
    			$this->commit();
    			return ['code'=>0,'msg'=>'取消关注成功'];
    		}catch(\Exception $e){
    			$this->rollback();
    			return ['code'=>1,'msg'=>'取消关注失败，请刷新重试'];
    		}
    		
    	}
    }

    /**
     * 获取指定用户的关注文章列表
     * @param  int $article_id 文章id
     * @param int $page 每页数量
     * @return  /think/Paginate
     */
    public function getMemberCollectArticleList($member_id)
    {
    	$articles = $this->where(['member_id'=>$member_id,'is_delete'=>0])->order('update_time','desc')->paginate(request()->param('list_rows'),false,['var_page' => 'page','query'=>request()->param()])->each(function($item,$key){
    		$article_info = model('Article')->where(['id'=>$item['article_id'],'status'=>0,'is_delete'=>0])->field('id,member_id,category_id,title,praise_num,click_num,collect_num,update_time')->find();
            if(!empty($article_info)){
                $item['article'] = $article_info;
                $item['member'] = $article_info->member;
                $item['category'] = $article_info->category;
                $item['comment_num'] = $article_info->comments()->count();
                $item['article_url'] = url('blog/article/readArticle',['id'=>$article_info->id]);
                $item['member_url'] = url('blog/member/readMember',['id'=>$article_info->member_id]);
                $item['category_url'] = url('blog/article/getCategory',['id'=>$article_info->category_id]);

            }
    	});
        return $articles;
    }
    
}
