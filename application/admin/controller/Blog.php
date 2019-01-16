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
        $bolg_category_sumnum = model('blog/ArticleCategory')->where(['status'=>0])->count();
        $bolg_category_todaynum = model('blog/ArticleCategory')->where(['status'=>0])->whereTime('create_time','between',$this->createTimeSearch('today'))->count();
        $BlogStatisticsInformation = [
            'blog_category_num'=>[$bolg_category_sumnum,$bolg_category_todaynum],
        ];
        return json($BlogStatisticsInformation);
    }

    /**
     * 生成时间搜索条件
     */
    public function createTimeSearch($time)
    {
        $now_date = date("Y-m-d");
        $yesterday_date = date("Y-m-d",strtotime("-1 day"));
        $last7_date = date("Y-m-d",strtotime("-7 day"));
        $last30_date = date("Y-m-d",strtotime("-30 day"));
        $time_search =  ['today'=>[$now_date.' 00:00:00',$now_date.' 23:59:59'],
                'yesterday'=>[$yesterday_date.' 00:00:00',$now_date.' 00:00:00'],
                'last7'=>[$last7_date.' 00:00:00',$now_date.' 00:00:00'],
                'last30'=>[$last30_date.' 00:00:00',$now_date.' 00:00:00'],
        ];
        return $time_search[$time];
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
