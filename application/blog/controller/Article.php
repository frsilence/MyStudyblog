<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;

class Article extends Appbasic
{
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
        if(empty($article)) return $this->fetch('public/404',['title'=>'404Page',
            'article_category'=>$this->article_category]);
        $data = [
            'article'=>$article,
            'title'=>$article->title,
            'article_category'=>$this->article_category,
            'navMenu'=>[
                    ['name'=>$article->category->category_title,'url'=>url('blog/article/getCategory',['id'=>$article->category->id])],
                    ['name'=>$article->title,'url'=>'#']]
        ];
        //return json($data);
        return $this->fetch('article_detail',$data);
    }

    /**
     * 保存新建的文章
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function addarticle(Request $request)
    {
        //验证请求字段
        $article_info = $request->post();
        $result = $this->validate($article_info,'app\blog\validate\Article.addarticle');
        if(true !== $result) return json(['code'=>1,'msg'=>$result,'token'=>$request->token()]);
        $save_article = model('Article')->addArticle($article_info);
        if($save_article == 0){
            return json(['code'=>1,'msg'=>'保存错误，请稍后尝试','token'=>$request->token()]);
        }else{
            return json(['code'=>0,'msg'=>'保存成功','article_id'=>$save_article,'token'=>$request->token()]);
        }
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
