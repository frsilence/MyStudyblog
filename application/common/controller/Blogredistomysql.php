<?php
/**
* 
*/
class Blogredistomydql
{
    protected $redis;
    protected $mysqli;
    protected $user_id;//用户id
    protected $article_id;//文章id
    protected $article_counts;//文章计数key
    protected $article_user_like_set;//post_id下的user_id集合
    protected $form_post_user;//用户点赞信息表
    protected $form_post_set;//文章点赞数量表

    function __construct()
    {
        //链接redis
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1',6379);
        $this->redis->auth('root');
        $mysqlConfig = [
            // 服务器地址
            'hostname'        => '127.0.0.1',
            // 数据库名
            'database'        => 'my_study',
            // 用户名
            'username'        => 'root',
            // 密码
            'password'        => 'root',
            // 端口
            'hostport'        => '',
        ];
        $this->mysqli = new Db($mysqlConfig);

    }
    public function praise_task()
    {
       //$this->redis->incr('blog_my_num',2);
       //出队，获取一个已缓存的被点赞文章
       $article_id = $this->queueOut(100);
       if(!empty($article_id)){
            $this->mysqli->mysql->autocommit(false);
            try{
                $this->article_id = $article_id;
                //获取redis中该文章的点赞数
                $redisNum = $this->findRedisPraiseCounts($this->article_id);
                //更新数据库中的文章点赞数
                $this->updateDbArticlePariseCounts($redisNum);
                $this->mysqli->mysql->commit();
            }catch(Exception $exception){
                $this->mysqli->mysql->rollback();
            }
       }
    }
    //出队操作
    public function queueOut($timeout)
    {
        return $this->redis->blPop('article_list', $timeout);
    }
    //获取文章点赞数
    public function findRedisPraiseCounts($article_id)
    {
        $this->article_id  =$article_id;
        $this->article_counts = 'article_'.$thsi->article_id.'_counts';
        $num = $this->redis->get($this->article_counts);
        return $num ? $num : 0;
    }
    //更新数据库中的文章点赞数
    function updateDbArticlePariseCounts($redisNum)
    {
        return 1;
    }
    
}
$task = new Blogredistomydql();
 while(true){
    sleep(2);
    $task->praise_task();
};
