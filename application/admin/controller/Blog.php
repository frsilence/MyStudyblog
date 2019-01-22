<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
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
    public function getAllCategory()
    {
        $article_categorys = model('blog/ArticleCategory')->select();
        $category_list = [];   
        $data = [
            'code' =>0,
            'message' => "success",
            'count' => 7,
            'data' => $article_categorys,
        ];
        return json($data);
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
