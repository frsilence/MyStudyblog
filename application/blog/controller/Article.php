<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;

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
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
