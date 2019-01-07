<?php

namespace app\common\job\blog;

use think\Controller;
use think\queue\Job;
use think\facade\Cache;
use Log;

class ArticlePraise extends Controller{
	/**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data)
    {
    	$isJobDone = $this->doArticlePraiseJob($data);
    	if($isJobDone){
    		//执行成功，删除任务
    		$job->delete();
    		print("任务已删除");
    	}else{
    		if($job->attempts()>3){
    			print("任务已执行3次");
    			$job->delete();
    		}
    	}
    }

    /**
     * hello任务执行时的函数
     */
    public function doArticlePraiseJob($data)
    {
    	//缓存更新至数据库
        Log::record('开始','error');
    	$config = config('cache.');
        $redis = new \Redis();
        $redis->connect($config['redis']['host'],$config['redis']['port']);
        $redis->auth($config['redis']['password']);
        $prefix = $config['redis']['prefix'];
        Log::record('redis服务已连接','error');
    	while($article_id = ($redis->lpop($prefix.'article_praise_list'))){
            Log::record('while');
            Log::record('pop出的id'.$article_id,'error');
    		//更新文章点赞
    		$redis_praise_counts = $this->findRedisArticlePraiseCounts($article_id);
            if($redis_praise_counts>0){
                $update_result = model('app\blog\model\Article')->updateArticlePraiseNum($article_id,$redis_praise_counts);
                Log::record('更新结果'.$update_result,'error');
                if(!$update_result) Cache::store('redis')->incr('article_praise_counts_'.$article_id,$redis_praise_counts);
            }else{
                Log::record('数据已被更新，当前值为0，无需更新','error');
            }               
    	}
    	//控制台提示信息
    	print("<info>Hello Job Started. job Data is: ".var_export($data,true)."</info> \n");
        print("<info>Hello Job is Fired at " . date('Y-m-d H:i:s') ."</info> \n");
        print("<info>Hello Job is Done!"."</info> \n");
        return true;
    }

    //获取缓存中的点赞数
    public function findRedisArticlePraiseCounts($article_id)
    {
    	$counts =  Cache::store('redis')->get('article_praise_counts_'.$article_id);
    	return $counts ? (int)Cache::store('redis')->pull('article_praise_counts_'.$article_id) : 0;
    }
}