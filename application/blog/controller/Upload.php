<?php

namespace app\blog\controller;

use think\Controller;
use think\Request;
use app\common\controller\Appbasic;
use Log;

/**
 * 上传文件控制器
 */
class Upload extends Appbasic
{
    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 图片上传方法,上传成功返回图片url
     * @param  think/Request $request
     * @return \think\Response
     */
    public function upload_image(Request $request)
    {
        $files = $request->file();
        if(is_array($files)){
            foreach ($files as $key => $value) {
                if($value->validate(['ext'=>'jpg,png,gif'])){
                    $info = $value->move('../public/static/uploads/image');
                    if($info){
                        $url[] = '/static/uploads/image/'.date('Ymd').'/'.$info->getFilename();
                    }else{
                        return json(['code'=>1,'msg'=>$value->getError()]);
                    }
                }else{
                    return json(['code'=>1,'msg'=>'文件格式不是图片']);
                }
            }
        }else{
                if($files->validate(['ext'=>'jpg,png,gif'])){
                    $info = $value->move('../public/static/uploads/image');
                    if($info){
                        $url[] = '/static/uploads/image/'.date('Ymd').'/'.$info->getFilename();
                    }else{
                        return json(['code'=>1,'msg'=>$value->getError()]);
                    }
                }else{
                    return json(['code'=>1,'msg'=>'文件格式不是图片']);
                }
        }
        return json(['code' => 0, 'msg' => '上传成功！', 'url' => $url]);
    }

    /**
     * 用户头像图片上传
     */
    public function upload_userimage(Request $request)
    {
        $files = $request->file('user_image');
        return dump($files);
        if($files->validate(['ext'=>'jpg,png,gif'])){
                    $info = $value->move('../public/static/uploads/image');
                    if($info){
                        $url = '/static/uploads/image/'.date('Ymd').'/'.$info->getFilename();
                    }else{
                        return json(['code'=>1,'msg'=>$value->getError()]);
                    }
        }else{
                return json(['code'=>1,'msg'=>'文件格式不是图片']);
             }
        return json(['code' => 0, 'msg' => '上传成功！', 'url' => $url]);
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
