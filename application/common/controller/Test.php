<?php

namespace app\common\controller;

use think\Controller;
use think\Request;
use think\Queue;

class Test extends Controller
{
    /**
     * 一个队列
     */
    public function actionHelloJob()
    {
        $jobClassName = 'app\common\job\Hello';
        $jobName = 'myHello1';
        $jobData = ['time'=>time(),'bizId'=>uniqid(),'name'=>'yfzhao'];
        $jobData2 = ['time'=>time(),'bizId'=>uniqid(),'name'=>'yfzhaozzzz'];
        $isPush = Queue::push($jobClassName,$jobData,$jobName);
        $isPush = Queue::push($jobClassName,$jobData2,'myHello2');
        if($isPush !== false){
            echo date('Y-m-d H:i:s').'新任务已提交队列'."<br>";
        }else{
            echo '新任务提交队列出错';
        }
    }








    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
