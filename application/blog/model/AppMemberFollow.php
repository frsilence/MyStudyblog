<?php

namespace app\blog\model;

use think\Model;
/**
 * 用户关注模型
 */
class AppMemberFollow extends Model
{
    protected $table = 'app_member_follow';

    /**
     * 添加关注者
     * @param int $member_id 被关注者ID
     * @param int $follower_id 关注者ID
     * @return int 关注操作完成信息 0:有用户不存在 1:已经关注 2:关注失败 3:关注成功
     */
    public function addMemberFollow($member_id,$follower_id)
    {
    	$member = model('AppMember');
    	//检查用户是否存在
    	if(empty($member->where(['id'=>$member_id,'status'=>0,'is_delete'=>0])->find()) || empty($member->where(['id'=>$follower_id,'status'=>0,'is_delete'=>0])->find())){
			return 0;
		};
    	//检查是否已经关注
    	if(!empty($this->where(['member_id'=>$member_id,'follower_id'=>$follower_id,'is_delete'=>0])->find())) return 1;
    	//执行关注
    	$this->startTrans();
    	try{
    		$follower = $this->where(['member_id'=>$member_id,'follower_id'=>$follower_id,'is_delete'=>1])->find();
    		if(!empty($follower)){
    			$follower->is_delete = 0;
    			$follower->save();
    			$this->commit();
    			return 3;
    		} else{
    			$this->save(['member_id'=>$member_id,'follower_id'=>$follower_id]);
    			$this->commit();
    			return 3;
    		}
    	} catch(\Exception $e){
    		$this->rollback();
    		return 2;
    	}

    }

    /**
     * 取消关注
     * @param int $member_id 被关注者ID
     * @param int $follower_id 关注者ID
     * @return int 取消关注操作完成信息 0:未关注 1:取消关注失败 2:取消关注成功
     */
    public function deleteMemberFollow($member_id,$follower_id)
    {
    	//检查是否关注
    	if (empty($this->where(['member_id'=>$member_id,'follower_id'=>$follower_id,'is_delete'=>0])->find())){
			return 0;
		};
    	$this->startTrans();
    	try{
    		$this->where(['member_id'=>$member_id,'follower_id'=>$follower_id,'is_delete'=>1])->update(['is_delete'=>0]);
    		$thsi->commit();
    		return 2;
    	}catch(\Exception $e){
    		$this->rollback();
    		return 1;
    	}
    }

    /**
     * 获取用户关注列表
     * @param int $member_id 用户ID
     * @param int $page 每页数量 
     * @return json 关注列表及数量
     */
    public function geyMemberFollow($member_id,$page=15)
    {
    	$follower_list = $this->where(['follower_id',$member_id,'is_delete'=>0])->order('create_at','desc')->column('member_id')->paginate($page,false)
    	->each(function($item,$key){
    		model('AppMember')->get($item['member_id']);
    	});
    	return $follower_list;

    }

    /**
     * 获取用户粉丝列表
     * @param int $member_id 用户ID
     * @param int $page 每页数量 
     * @return json 粉丝列表及数量
     */
    public function geyMemberFans($member_id,$page=15)
    {
    	$follower_list = $this->where(['member_id',$member_id,'is_delete'=>0])->order('create_at','desc')->column('follower_id')->paginate($page,false)
    	->each(function($item,$key){
    		$item['article_info'] = model('AppMember')->getArticleByArticleId($item['article_id']);
    	});
    	return $follower_list;

    }
}
