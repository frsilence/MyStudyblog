<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;
use think\facade\Cache;
use think\Queue;
use Log;
use think\facade\Cookie;

class Article extends Appbasic
{
    /**
     * 检测用户登录中间件
     */
    protected $middleware = [
        'BlogAuth' => ['only'=>['addArticlePage','addArticle','addComment','collectArticle','uncollectArticle']],
    ];




    /**
     * 显示添加文章页面
     *
     * @return \think\Response
     */
    public function addArticlePage()
    {
        $data = [
            'title'=>"新建文章",
            'article_category'=>$this->article_category,
            'navMenu'=>[
                ['name'=>'新建文章','url'=>url('blog/article/addArticlePage')]]
                ];
        return $this->fetch('addarticle',$data);
    }

    /**
     * 显示文章详情页
     * @param   $id 文章id
     * @return  \think\Response
     */
    public function readArticle($id)
    {
        //Cookie::delete('article_viewlist');
        //return json(Cookie::get('PHPSESSID'));
        $article = model('Article')->getArticleByArticleId($id);
        if(empty($article)) return $this->fetch('public\404',['title'=>'404Page',
            'article_category'=>$this->article_category]);
        $data = [
            'article'=>$article,
            'title'=>$article->title,
            'article_category'=>$this->article_category,
            'navMenu'=>[
                    ['name'=>$article->category->category_title,'url'=>url('blog/article/getCategory',['id'=>$article->category->id])],
                    ['name'=>$article->title,'url'=>'#']]
        ];
        $data['last_article'] = (!empty(model('Article')->getLatestArticle($id))) ? model('Article')->getLastArticle($id) : NULL;
        $data['next_article'] = (!empty(model('Article')->getNextArticle($id))) ? model('Article')->getNextArticle($id) : NULL;
        //新增文章点击量
        if(Cookie::has('article_viewlist')){
            $article_viewlist = Cookie::get('article_viewlist');
            if(!in_array($id,$article_viewlist) && count($article_viewlist)<500){
                $article_viewlist[] = $id;
                Cookie::set('article_viewlist',$article_viewlist);
                model('Article')->where(['id' => $id])->setInc('click_num', 1);
            }           
        }else{
            $article_viewlist = [$id];
            Cookie::set('article_viewlist',$article_viewlist,30);
            model('Article')->where(['id' => $id])->setInc('click_num', 1);
        }
        //return json($data);
        return $this->fetch('article_detail',$data);
    }

    /**
     * 显示文章分类
     * @param  $id 分类id
     * @return think\Response
     */
    public function getCategory($id)
    {
        $category = model('ArticleCategory')->where(['id'=>$id,'status'=>0])->find();
        if(empty($category)) return $this->fetch('public\404',['title'=>'404','article_category'=>$this->article_category,]);
        $data = [
            'title' => $category->category_title,
            'category' => $category,
            'article_category'=>$this->article_category,
            'navMenu'=>[
                    ['name'=>$category->category_title,'url'=>url('blog/article/getCategory',['id'=>$category->id])],
                    ]
        ];
        //return json($data);
        return $this->fetch('article_category',$data);
    }

    public function getCategoryArticleList(Request $request,$id)
    {
        return model('Article')->getArticleByCategoryId($id);
    }

    /**
     * 显示所有文章分类
     */
    public function getAllCategory()
    {
        $data = [
            'title'=>'所有分类',
            'article_category'=>$this->article_category,
        ];
        return $this->fetch('article_allcategory',$data);
    }

    /**
     * 保存新建的文章
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function addArticle(Request $request)
    {
        //验证请求字段
        $article_info = $request->post();
        $result = $this->validate($article_info,'app\blog\validate\Article.addarticle');
        if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>$request->token()]);
        $save_article = model('Article')->addArticle($article_info);
        if($save_article == 1){
            return json(['code'=>1,'msg'=>'保存错误，请稍后尝试','token'=>$request->token()]);
        }else{
            return json(['code'=>0,'msg'=>'保存成功','article_id'=>$save_article,'token'=>$request->token()]);
        }
    }

    /**
     * 添加评论
     * @param think/Request
     * @param return json
     */
    public function addComment(Request $request)
    {
        //验证输入参数
        $comment_info = $request->post();
        $result = $this->validate($comment_info,'app\blog\validate\Article.addcomment');
        if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>$request->token()]);
        $comment_info['member_id'] = session('member.id');
        $save_comment = model('ArticleComment')->addComment($comment_info);
        if($save_comment == 0){
            return json(['code'=>0,'msg'=>'提交成功','token'=>$request->token()]);
        }else{
            return json(['code'=>1,'msg'=>'保存错误，请稍后尝试','token'=>$request->token()]);
        }

    }

    /**
     * 收藏文章
     * @param int $id 文章id
     * @return  json
     */
    public function collectArticle($id)
    {
        if(!session('?member.id')) return json(['code'=>1,'msg'=>'未登录,先登录再操作']);
        $member_id = session('member.id');
        $result = model('ArticleMember')->addArticleToMember($member_id,$id);
        return json($result);
    }

    /**
     * 检测文章与用户的关注关系
     */
    public function checkArticleMember(Request $request)
    {
        if($request->has('article_id') && $request->has('member_id')){
            $result = model('ArticleMember')->checkArticleMember($request->post('article_id'),session('member.id'));
        }else{
            $result = ['code'=>1,'msg'=>'获取参数错误'];
        }
        return json($result);
    }

    /**
     *取消收藏文章
     * @param int $id 文章id
     * @return  json
     */
    public function uncollectArticle($id)
    {
        if(!session('?member.id')) return json(['code'=>1,'msg'=>'未登录,先登录再操作']);
        $member_id = session('member.id');
        $result = model('ArticleMember')->deleteArticleToMember($member_id,$id);
        return json($result);
    }

    /**
     * 点赞文章
     *
     * @param  int  $id 文章id
     * @return \think\Response
     */
    public function addPraise($id)
    {
        if(!session('?member.id')) return json(['code'=>1,'msg'=>'未登录用户无法操作']);
        $member_id = session('member.id');
        $article_id = $id;
        Log::record('用户id:'.$member_id.'文章id：'.$article_id,'error');
        //建立点赞文章列表list
        /*
        $article_list = Cache::store('redis')->remember('article_praise_list',function(){
            return [];
        });
        */
        //建立点赞记录缓存
        if(Cache::store('redis')->get('article_praise_recorde_'.$article_id.'_'.$member_id)){
            Log::record('距离当前账户对该文章的上一次点赞时间不足1分钟，请稍后再试！','error');
            return json(['code'=>2,'msg'=>'距离当前账户对该文章的上一次点赞时间不足0.5分钟，请稍后再试！']);
        }
        Cache::store('redis')->set('article_praise_recorde_'.$article_id.'_'.$member_id,1,0.1);
        Log::record('建立点赞记录:'.'article_praise_recorde_'.$article_id.'_'.$member_id,'error');
        if(Cache::store('redis')->get('article_praise_counts_'.$article_id)){
            Cache::store('redis')->inc('article_praise_counts_'.$article_id);
            Log::record('该文章点赞数量缓存+1'.'article_praise_counts_'.$article_id,'error');
        }else{
            Cache::store('redis')->set('article_praise_counts_'.$article_id,1);
            Log::record('首次建立点赞数量缓存'.'article_praise_counts_'.$article_id,'error');
        }
        //点赞文章id添加
        $config = config('cache.');
        $redis = new \Redis();
        $redis->connect($config['redis']['host'],$config['redis']['port']);
        $redis->auth($config['redis']['password']);
        $prefix = $config['redis']['prefix'];
        $redis->rpush($prefix.'article_praise_list',$article_id);
        Log::record('点赞文章'.$article_id.'添加入待更新列表'.$prefix.'article_praise_list'.'为rPush','error');
        //添加点赞消息队列
        $isPush =  addBlogQueue('ArticlePraiseUpdate','点赞消息推送队列');
        if($isPush !== false){
            Log::record(date('Y-m-d H:i:s').'新任务已提交队列'."<br>",'error');
            }else{
                Log::record('新任务提交队列出错','error');
        }
        $mysql_praise_counts = model('Article')->where(['id'=>$article_id])->field('praise_num')->find();
        if(empty($mysql_praise_counts)){
            return json(['code'=>1,'msg'=>'文章不存在']);
        }else{
            $praise_counts = $mysql_praise_counts->praise_num+(int)Cache::store('redis')->get('article_praise_counts_'.$article_id);
            Log::record('返回文章点赞数:'.$praise_counts,'error');
            return json(['code'=>0,'msg'=>'点赞成功','praise_counts'=>$praise_counts]);
        }
        

        
        
    }

    /**
     * 搜索页面
     * @param think\request
     * @return   think\Paginator
     */
    public function getSearchArticle(Request $request)
    {
        $data=['title'=>'search',
        'article_category'=>$this->article_category,];
        return $this->fetch('search',$data);
    }

    /**
     * 搜索文章
     * @param  think\request
     * @param  think\Paginator
     */
    public function searchArticle(Request $request)
    {
        return json($request->post('category_id'));
    }



    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
