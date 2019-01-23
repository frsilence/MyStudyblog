<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\controller\Adminbasic;

class Blog extends Adminbasic
{
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
        $post_info['category_createtimemax'] !='' ? $category_createtimemax = $post_info['category_createtimemax'] : $category_createtimemax = date("Y-m-d");
        if($post_info['categort_searchtitle'] !=''){
            $article_categorys = model('blog/ArticleCategory')->where('category_title','LIKE',"%{$post_info['categort_searchtitle']}%")->order('id','asc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()]);
        }else{
            $article_categorys = model('blog/ArticleCategory')->whereTime('create_time','between',[$category_createtimemin,$category_createtimemax])->order('id','asc')->paginate(request()->param('limit'),false,['var_page' => 'page','query'=>request()->param()]);
        }
        //return json($article_categorys);   
        $data = [
            'code' =>0,
            'message' => "success",
            'count' => $article_categorys->toArray()['total'],
            'data' => $article_categorys->toArray()['data'],
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
