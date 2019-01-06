<?php
namespace app\common\job;
use think\queue\Job;
use think\facade\Cache;
class Hello{
	/**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data)
    {
    	$isJobDone = $this->doHelloJob($data);
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
    public function doHelloJob($data)
    {
    	Cache::store('redis')->inc('my_num',2);
    	print("<info>Hello Job Started. job Data is: ".var_export($data,true)."</info> \n");
        print("<info>Hello Job is Fired at " . date('Y-m-d H:i:s') ."</info> \n");
        print("<info>Hello Job is Done!"."</info> \n");
        return true;
    }
}