<?php
//blog应用公共函数
use think\Queue;
if(!function_exists('addBlogQueue')){
	/**
	 * blog应用消息推送队列
	 * @param string $job_name 推送队列名称
	 * @param [type] $job_msg  推送消息提示信息
	 */
	function addBlogQueue($job_name,$job_msg)
	{
		$job_classname_list = [
			'ArticlePraiseUpdate' => 'app/common/job/blog/ArticlePraise',
		];
		$jobClassName = $job_classname_list[$job_name];
		$jobData = ['time'=>time(),'jobId'=>uniqid(),'name'=>$job_msg];
		$isPush = Queue::push($jobClassName,$jobData,$job_name);
		if($isPush !== false){
			Log::record(date('Y-m-d H:i:s').'新任务已提交队列'.$job_name."<br>",'error');
			return true;
		}else{
			Log::record('队列提交出错','error');
			return false;
		}
	}
}