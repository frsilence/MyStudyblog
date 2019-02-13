<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\Adminbasic;

class Blog extends Adminbasic
{


    /**
     * 检测用户登录中间件
     */
    protected $middleware = ['AdminAuth'];
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return 's';
    }
    /**
     * 管理界面/Blog管理/分类管理 页面
     * @return [type] [description]
     */
    public function getBlogCategory()
    {
        return $this->fetch('category_manage');
    }
    /**
     * 管理界面/Blog管理/分类管理/分类编辑页面
     * @param $id 分类id
     */
    public function getBlogCategoryEdit($id)
    {
        $category = model('blog/ArticleCategory')->where('id',$id)->find();
        if(empty($category)) return '未找到该文章分类，请刷新该页面再试！';
        return $this->fetch('category_manageedit',['category'=>$category]);
    }
    /**
     * 管理界面/Blog管理/分类管理/新增分类页面
     */
    public function addBlogCategory()
    {
        return $this->fetch('category_manageadd');
    }

    /**
     * 管理界面/Blog管理/文章管理 页面
     */
    public function getBlogArticle()
    {
        $categorys = model('blog/ArticleCategory')->getCategoryList();
        return $this->fetch('article_manage',['article_category'=>$categorys]);
    }
    
    /**
     * 管理界面/Blog管理/分类管理/分类编辑页面
     * @param $id 分类id
     */
    public function getBlogArticleEdit($id)
    {
        $article = model('blog/Article')->where('id',$id)->find();
        if(empty($article)) return '未找到该文章，请刷新该页面再试！';
        $categorys = model('blog/ArticleCategory')->getCategoryList();
        $article_tags = $article->tags;
        $tags = '';
        foreach($article_tags as $key=>$item){
            $tags = $tags.$item->name.';';
        }
        //return json($tags);
        return $this->fetch('article_manageedit',['article'=>$article,'article_category'=>$categorys,'tags'=>$tags]);
    }
    /**
     * 获取Blog统计信息
     *
     * @return \think\Response
     */
    public function getBlogStatisticsInformation()
    {
        //return json($this->createTimeSearch('today'));
        //blog 分类统计
        $blog_category_sumnum = model('blog/ArticleCategory')->where(['status'=>0])->count();
        $blog_category_todaynum = model('blog/ArticleCategory')->where(['status'=>0])->whereTime('create_time','between',$this->createTimeSearch('today'))->count();
        $blog_category_yesterdaynum = model('blog/ArticleCategory')->where(['status'=>0])->whereTime('create_time','between',$this->createTimeSearch('yesterday'))->count();
        $blog_category_last7num = model('blog/ArticleCategory')->where(['status'=>0])->whereTime('create_time','between',$this->createTimeSearch('last7'))->count();
        $blog_category_last30num = model('blog/ArticleCategory')->where(['status'=>0])->whereTime('create_time','between',$this->createTimeSearch('last30'))->count();
        //blog 文章统计
        $blog_article_sumnum = model('blog/Article')->where(['is_delete'=>0,'status'=>0])->count();
        $blog_article_todaynum = model('blog/Article')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('today'))->count();
        $blog_article_yesternum = model('blog/Article')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('yesterday'))->count();
        $blog_article_last7num = model('blog/Article')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('last7'))->count();
        $blog_article_last30num = model('blog/Article')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('last30'))->count();
        //评论统计
        $blog_comment_sumnum = model('blog/ArticleComment')->where(['is_delete'=>0])->count();
        $blog_comment_todaynum = model('blog/ArticleComment')->where(['is_delete'=>0])->whereTime('create_time','between',$this->createTimeSearch('today'))->count();
        $blog_comment_yesternum = model('blog/ArticleComment')->where(['is_delete'=>0])->whereTime('create_time','between',$this->createTimeSearch('yesterday'))->count();
        $blog_comment_last7num = model('blog/ArticleComment')->where(['is_delete'=>0])->whereTime('create_time','between',$this->createTimeSearch('last7'))->count();
        $blog_comment_last30num = model('blog/ArticleComment')->where(['is_delete'=>0])->whereTime('create_time','between',$this->createTimeSearch('last30'))->count();
        //用户统计
        $blog_member_sumnum = model('blog/AppMember')->where(['is_delete'=>0,'status'=>0])->count();
        $blog_member_todaynum = model('blog/AppMember')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('today'))->count();
        $blog_member_yesternum = model('blog/AppMember')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('yesterday'))->count();
        $blog_member_last7num = model('blog/AppMember')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('last7'))->count();
        $blog_member_last30num = model('blog/AppMember')->where(['is_delete'=>0,'status'=>0])->whereTime('create_time','between',$this->createTimeSearch('last30'))->count();
        //注册用户登录量统计
        $blog_memberlogin_sumnum = model('blog/AppMemberLoginRecord')->where(['type'=>0])->count();
        $blog_memberlogin_todaynum = model('blog/AppMemberLoginRecord')->where(['type'=>0])->whereTime('create_time','between',$this->createTimeSearch('today'))->count();
        $blog_memberlogin_yesternum = model('blog/AppMemberLoginRecord')->where(['type'=>0])->whereTime('create_time','between',$this->createTimeSearch('yesterday'))->count();
        $blog_memberlogin_last7num = model('blog/AppMemberLoginRecord')->where(['type'=>0])->whereTime('create_time','between',$this->createTimeSearch('last7'))->count();
        $blog_memberlogin_last30num = model('blog/AppMemberLoginRecord')->where(['type'=>0])->whereTime('create_time','between',$this->createTimeSearch('last30'))->count();
        $BlogStatisticsInformation = [
            'blog_category_num'=>[$blog_category_sumnum,$blog_category_todaynum,$blog_category_yesterdaynum,$blog_category_last7num,$blog_category_last30num],
            'blog_article_num'=>[$blog_article_sumnum,$blog_article_todaynum,$blog_article_yesternum,$blog_article_last7num,$blog_article_last30num],
            'blog_comment_num' =>[$blog_comment_sumnum,$blog_comment_todaynum,$blog_comment_yesternum,$blog_comment_last7num,$blog_comment_last30num],
            'blog_member_num' =>[$blog_member_sumnum,$blog_member_todaynum,$blog_member_yesternum,$blog_member_last7num,$blog_member_last30num],
            'blog_memberlogin_num'=>[$blog_memberlogin_sumnum,$blog_memberlogin_todaynum,$blog_memberlogin_yesternum,$blog_memberlogin_last7num,$blog_memberlogin_last30num]
        ];     
        return ($BlogStatisticsInformation);
       
        
    }

    /**
     * 生成时间搜索条件
     */
    public function createTimeSearch($time)
    {
        $nextday_date = date("Y-m-d",strtotime("1 day"));
        $now_date = date("Y-m-d");
        $yesterday_date = date("Y-m-d",strtotime("-1 day"));
        $last7_date = date("Y-m-d",strtotime("-7 day"));
        $last30_date = date("Y-m-d",strtotime("-30 day"));
        $time_search =  ['today'=>[$now_date,$nextday_date],
                'yesterday'=>[$yesterday_date,$now_date],
                'last7'=>[$last7_date,$nextday_date],
                'last30'=>[$last30_date,$nextday_date],
        ];
        return $time_search[$time];
    }

    /**
     * 获取文章分类
     */
    public function getCategoryList(Request $request)
    {
        //验证输入参数
        $post_info = $request->get();
        $result = $this->validate($post_info,'app\admin\validate\Blog.category_search');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        $post_info['category_createtimemin'] !='' ? $category_createtimemin = $post_info['category_createtimemin'] : $category_createtimemin = '2018-01-01';
        $post_info['category_createtimemax'] !='' ? $category_createtimemax = $post_info['category_createtimemax'] : $category_createtimemax = date("Y-m-d",strtotime("1 day"));;
        if($post_info['category_searchtitle'] !=''){
            $adminusers = model('blog/ArticleCategory')->where('category_title','LIKE',"%{$post_info['category_searchtitle']}%")->whereTime('create_time','between',[$category_createtimemin,$category_createtimemax])->order('id','asc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()]);
        }else{
            $adminusers = model('blog/ArticleCategory')->whereTime('create_time','between',[$category_createtimemin,$category_createtimemax])->order('id','asc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()]);
        }
        //return json($article_categorys);   
        $data = [
            'code' =>0,
            'message' => "success",
            'count' => $adminusers->toArray()['total'],
            'data' => $adminusers->toArray()['data'],
        ];
        return json($data);
    }

    /**
     * 修改文章分类的状态
     */
    public function categorystatuschange(Request $request)
    {
        //验证输入参数
        $post_info = $request->post();
        $result = $this->validate($post_info,'app\admin\validate\Blog.category_statuschange');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //执行
        $category = model('blog/ArticleCategory')->where(['id'=>$post_info['category_id']])->find();
        if(empty($category)){
            return json(['code'=>1,'msg'=>'分类不存在']);
        }else{
            //判断分类状态是否已改变
            if($category->status != $post_info['category_status']) return json(['code'=>2,'msg'=>'状态已经修改，请刷新页面再试']);
            //未改变
            $category->status == 1 ? list($msg,$status) = ['启用成功',0] : list($msg,$status) = ['禁用成功',1];
            $result = model('blog/ArticleCategory')->where(['id'=>$post_info['category_id']])->update(['status'=>$status]);
            if($result>=1){
                //更新该分类下的文章状态
                $articles = model('blog/Article')->where(['category_id'=>$post_info['category_id']])->select();
                foreach ($articles as $key => $value) {
                    $value->status = $status;
                    $value->save();
                }
                //日志记录
                return json(['code'=>0,'msg'=>$msg,'status'=>$status]);
            }
            return json(['code'=>1,'msg'=>'状态修改失败']);    
        };
        
    }

    /**
     * 更新文章分类信息
     * @param int $id 分类ID
     * @param $category_title 新的分类标题
     * @param $category_content 新的分类简介
     */
    public function updateCategory(Request $request)
    {
        //验证输入参数
        $post_info = $request->post();
        $result = $this->validate($post_info,'app\admin\validate\Blog.category_update');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //执行
        try{
            $result = model('blog/ArticleCategory')->where('id',$post_info['category_id'])->update([
                    'category_title'=>$post_info['category_title'],
                    'category_content'=>$post_info['category_content'],
                ]);
            if($result>=1){
                return json(['code'=>0,'msg'=>'文章分类信息更新成功']);
            }else{
                return json(['code'=>1,'msg'=>'文章分类信息更新失败']);
            }
        }catch (\Exception $e){
            return json(['code'=>1,'msg'=>'文章分类信息更新失败error']);
        }
        
    }

    /**
     *新增文章分类
     *@param $category_title 新的分类标题
     *@param $category_content 新的分类简介
     */
    public function addCategory(Request $request)
    {
        //验证输入参数
        $post_info = $request->post();
        $result = $this->validate($post_info,'app\admin\validate\Blog.category_add');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //执行
        $save =  model('blog/ArticleCategory')->addBlogCategory($post_info['category_title'],$post_info['category_content']);
        if($save['code']==0){
            return json(['code'=>$save['code'],'msg'=>$save['msg'],'category_id'=>$save['category_id']]);
        }else{
            return json(['code'=>$save['code'],'msg'=>$save['msg']]);
        }
    }

    /**
     * 删除文章分类
     * @param   $categoryid_list 文章分类ID数组
     */
    public function deleteCategory(Request $request)
    {
        //验证输入参数
        $post_info = $request->delete();
        $result = $this->validate($post_info,'app\admin\validate\Blog.category_delete');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //执行
        $delete =  model('blog/ArticleCategory')->deleteBlogCategory($post_info['categoryid_list']);
        if($delete['code']==0){
            return json(['code'=>$delete['code'],'msg'=>$delete['msg']]);
        }else{
            return json(['code'=>$delete['code'],'msg'=>$delete['msg']]);
        }
    }

    /**
     *获取文章列表
     */
    public function getArticleList(Request $request)
    {
        //验证输入参数
        $post_info = $request->get();
        $result = $this->validate($post_info,'app\admin\validate\Blog.article_search');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        $post_info['article_createtimemin'] !='' ? $article_createtimemin = $post_info['article_createtimemin'] : $article_createtimemin = '2018-01-01';
        $post_info['article_createtimemax'] !='' ? $article_createtimemax = $post_info['article_createtimemax'] : $article_createtimemax = date("Y-m-d",strtotime("1 day"));;
        $search_term = [['is_delete','=','0']];
        if($post_info['article_searchtitle']!='') $search_term[]=['title','LIKE',"%{$post_info['article_searchtitle']}%"];
        if($post_info['article_searchcategory']!=0) $search_term[]=['category_id','=',$post_info['article_category']];
        if($post_info['article_user']!=''){
            $user = model('blog/AppMember')->where(['username'=>$post_info['article_user']])->find();
            if(!empty($user)) $search_term[]=['member_id','=',$user->id];
        }
        if($post_info['article_searchstatus']!='') $search_term[]=['status','=',$post_info['article_searchstatus']];
        $article_list = model('blog/Article')->where('title','LIKE',"%{$post_info['article_searchtitle']}%")->where($search_term)->whereTime('create_time','between',[$article_createtimemin,$article_createtimemax])->field('id,member_id,category_id,member_id,title,status,create_time,update_time')->order('create_time','desc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()])->each(function($item,$key){
            $item['article_user'] = $item->member['username'];
            $item['article_category'] = $item->category['category_title'];
            $item['article_url'] = url('blog/article/readArticle',['id'=>$item->id]);
        });
        //return json($article_list);   
        $data = [
            'code' =>0,
            'message' => "success",
            'count' => $article_list->toArray()['total'],
            'data' => $article_list->toArray()['data'],
        ];
        return json($data);
    }

    /**
     * 修改文章的启用状态
     */
    public function articlestatuschange(Request $request)
    {
        //验证输入参数
        $post_info = $request->post();
        $result = $this->validate($post_info,'app\admin\validate\Blog.article_statuschange');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //执行
        $article = model('blog/Article')->where(['id'=>$post_info['article_id']])->find();
        if(empty($article)){
            return json(['code'=>1,'msg'=>'文章不存在']);
        }else{
            //判断文章状态是否已改变
            if($article->status != $post_info['article_status']) return json(['code'=>2,'msg'=>'状态已经修改，请刷新页面再试']);
            //未改变
            $article->status == 1 ? list($msg,$status) = ['启用成功',0] : list($msg,$status) = ['禁用成功',1];
            $result = model('blog/Article')->where(['id'=>$post_info['article_id']])->update(['status'=>$status]);
            if($result>=1){
                //日志记录
                return json(['code'=>0,'msg'=>$msg,'status'=>$status]);
            }
            return json(['code'=>1,'msg'=>'状态修改失败']);    
        };
        
    }

    /**
     * 删除文章
     * @param   $articleid_list 文章分类ID数组
     */
    public function deleteArticle(Request $request)
    {
        //验证输入参数
        $post_info = $request->delete();
        $result = $this->validate($post_info,'app\admin\validate\Blog.article_delete');
        if(true !== $result) return json(['code'=>1,'msg'=>$result]);
        //执行
        $delete =  model('blog/Article')->deleteBlogArticle($post_info['articleid_list']);
        if($delete['code']==0){
            return json(['code'=>$delete['code'],'msg'=>$delete['msg']]);
        }else{
            return json(['code'=>$delete['code'],'msg'=>$delete['msg']]);
        }
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
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
