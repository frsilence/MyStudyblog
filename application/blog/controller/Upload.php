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
     * 用户登录中间件
     */
    protected $middleware = [
        'BlogAuth' => ['only'=>['upload_image','upload_userimage']],
    ];

    /**
     * 图片上传方法,上传成功返回图片url
     * @param  think/Request $request
     * @return \think\Response
     */
    public function upload_image(Request $request)
    {
        $files = $request->file('image');
        if(is_array($files)){
            foreach ($files as $value) {
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
            return json(['code'=>0,'msg'=>'多张图片上传成功','url'=>$url]);
        }else{
                if($files->validate(['ext'=>'jpg,png,gif'])){
                    $info = $files->move('../public/static/uploads/image');
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
        if($files->validate(['ext'=>'jpg,png,gif'])){
                    $image = \think\Image::open($files);
                    $type = $image->type();
                    $width = $image->width();
                    $height = $image->height();
                    if(!is_dir('../public/static/uploads/image/'.date('Ymd'))) mkdir('../public/static/uploads/image/'.date('Ymd'));
                    if($width>=300 || $height>=300){
                        $url = '/static/uploads/image/'.date('Ymd').'/'.time().rand(1000,9999).'.'.$type;
                        $save_url = '../public'.$url;
                        $image->crop(300,300)->save($save_url);
                    }else{
                        $url = '/static/uploads/image'.'/'.date('Ymd').'/'.time().rand(1000,9999).'.'.$type;
                        $save_url = '../public'.$url;
                        $image->save($save_url);
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
