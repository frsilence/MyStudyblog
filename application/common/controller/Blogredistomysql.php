<?php
/**
* 
*/
class Blogredistomydql
{
    protected $redis;
    function __construct()
    {
        //链接redis
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1',6379);
        $this->redis->auth('root');
    }
    public function praise_task()
    {
       //$this->redis->incr('blog_my_num',2);
       //出队，获取一个已缓存的被点赞文章
       $article = $this->queueOut($time_out);
    }
    
}
$task = new Blogredistomydql();
 while(true){
    sleep(2);
    $task->praise_task();
};
